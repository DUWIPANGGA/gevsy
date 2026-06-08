<?php

namespace App\Http\Controllers;

use App\Enums\MeetingPipelineStage;
use App\Enums\MeetingPipelineStatus;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\RekamanAudio;
use App\Services\GeminiNotulensiSummarizerService;
use App\Services\MeetingAiPipelineDispatcher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    private const MAX_PARTICIPANTS = 999;

    public function index()
    {
        return view('meeting.join');
    }

    public function userAgenda()
    {
        $meetings = Meeting::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->get();

        return view('meeting.agenda', compact('meetings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rapat' => 'required|string|max:255',
            'jenis_rapat' => 'nullable|in:online,offline',
            'tipe_rapat' => 'required_without:waktu_rapat|in:instant,scheduled',
            'waktu_rapat' => 'required_without:tipe_rapat|in:instant,scheduled',
            'tanggal' => 'nullable|date|after_or_equal:today',
            'waktu' => 'nullable|date_format:H:i',
            'deskripsi_rapat' => 'nullable|string|max:5000',
            'keterangan' => 'nullable|string|max:5000',
        ]);

        $waktuRapat = $validated['waktu_rapat'] ?? $validated['tipe_rapat'] ?? 'instant';
        $jenisRapat = $validated['jenis_rapat'] ?? 'online';
        $isInstant = $waktuRapat === 'instant';
        $tipeRapatDb = $jenisRapat === 'online' ? 'Online' : 'Offline';

        $meeting = Meeting::create([
            'nama_rapat' => $validated['nama_rapat'],
            'deskripsi_rapat' => $validated['deskripsi_rapat'] ?? $validated['keterangan'] ?? null,
            'tanggal' => $isInstant ? now()->toDateString() : $validated['tanggal'],
            'waktu' => $isInstant ? now()->toTimeString() : $validated['waktu'],
            'tipe_rapat' => $tipeRapatDb,
            'dibuat_oleh' => auth()->id(),
            'status_rapat' => $isInstant ? 'Berlangsung' : 'Menunggu',
        ]);

        if ($isInstant && $jenisRapat === 'online') {
            return redirect()->route('meeting.room', $meeting->id);
        }

        return redirect()->route('meeting.agenda')->with('success', 'Rapat berhasil dijadwalkan!');
    }

    public function join(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|integer|exists:meetings,id',
        ]);

        $meeting = Meeting::query()->findOrFail($request->integer('meeting_id'));
        $userId = auth()->id();

        if (! $this->enterMeeting($meeting, $userId)) {
            return back()->withErrors([
                'meeting_id' => 'Rapat sudah penuh. Maksimal 5 peserta aktif.',
            ]);
        }

        return redirect()->route('meeting.room', $meeting->id);
    }

    public function room($id)
    {
        $meeting = Meeting::query()->findOrFail($id);
        $userId = auth()->id();

        if (! $this->enterMeeting($meeting, $userId)) {
            return redirect()
                ->route('meeting.join.form')
                ->withErrors([
                    'meeting_id' => 'Rapat sudah penuh. Maksimal 5 peserta aktif.',
                ]);
        }

        $host = request()->getHost();
        $liveKitPort = parse_url(config('livekit.server_url'), PHP_URL_PORT) ?: '7880';
        $liveKitUrl = "ws://{$host}:{$liveKitPort}";

        return view('meeting.room', compact('meeting', 'liveKitUrl'));
    }

    public function leave(Meeting $meeting)
    {
        $meeting->participants()
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->update(['left_at' => now()]);

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }

    public function uploadRecording(Request $request, Meeting $meeting)
    {
        $this->assertActiveParticipant($meeting);

        if ($meeting->pipeline_status === MeetingPipelineStatus::Processing->value) {
            abort(423, 'Meeting sedang diproses. Tunggu hingga selesai.');
        }

        $validated = $request->validate([
            'recording' => 'required|file|max:2097152',
            'duration_seconds' => 'nullable|integer|min:1|max:86400',
        ]);

        $file = $validated['recording'];
        $extension = strtolower($file->getClientOriginalExtension() ?: 'webm');
        $relativePath = 'meetings/'.$meeting->id.'/raw-'.uniqid('', true).'.'.$extension;

        Storage::disk('local')->putFileAs(
            'meetings/'.$meeting->id,
            $file,
            basename($relativePath)
        );

        $mimeType = $file->getMimeType();
        $tipeRekaman = str_starts_with($mimeType, 'video/') ? 'video' : 'audio';

        $rekaman = RekamanAudio::query()->create([
            'meeting_id' => $meeting->id,
            'file_audio' => $relativePath,
            'raw_recording_path' => $relativePath,
            'mime_type' => $mimeType,
            'file_size_bytes' => $file->getSize(),
            'duration_seconds' => $validated['duration_seconds'] ?? null,
            'durasi' => isset($validated['duration_seconds'])
                ? (string) $validated['duration_seconds'].'s'
                : '-',
            'tanggal_upload' => now()->toDateString(),
            'tipe_rekaman' => $tipeRekaman,
        ]);

        if ($tipeRekaman === 'audio') {
            MeetingAiPipelineDispatcher::startFromRekaman($rekaman);
        }

        return response()->json([
            'status' => 'queued',
            'rekaman_audio_id' => $rekaman->id,
        ]);
    }

    public function pipelineStatus(Meeting $meeting)
    {
        $this->assertActiveParticipant($meeting);

        $meeting->load(['notulensi:id,meeting_id,file_pdf']);

        return response()->json([
            'pipeline_status' => $meeting->pipeline_status,
            'pipeline_stage' => $meeting->pipeline_stage,
            'pipeline_error' => $meeting->pipeline_error,
            'has_pdf' => filled($meeting->notulensi?->file_pdf),
        ]);
    }

    public function saveLiveTranscript(Request $request, Meeting $meeting)
    {
        $this->assertActiveParticipant($meeting);

        $validated = $request->validate([
            'text' => 'required|string|max:10000',
            'speaker_id' => 'nullable',
            'speaker_name' => 'nullable|string|max:255',
        ]);

        $text = trim($validated['text']);

        if ($text !== '') {
            $senderName = $validated['speaker_name'] ?? (auth()->user()?->name ?? 'Participant');
            $senderId = $validated['speaker_id'] ?? auth()->id();

            // Save/Append to Transkrip database record
            $transkrip = \App\Models\Transkrip::query()->firstOrCreate(
                ['meeting_id' => $meeting->id],
                [
                    'hasil_transkrip' => '',
                    'openai_model' => 'faster-whisper',
                    'tanggal_generate' => now()->toDateString(),
                ]
            );

            $existingText = $transkrip->hasil_transkrip;
            $separator = $existingText === '' ? '' : "\n";
            $transkrip->update([
                'hasil_transkrip' => $existingText.$separator.$senderName.': '.$text,
            ]);

            // Broadcast via WebRTCSignal event so other participants receive it
            broadcast(new \App\Events\WebRTCSignal($meeting->id, [
                'type' => 'transcription',
                'sender_id' => $senderId,
                'sender_name' => $senderName,
                'text' => $text,
            ]))->toOthers();
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function startRecording(Meeting $meeting)
    {
        $this->assertActiveParticipant($meeting);

        // Delete any existing transcript for this meeting
        $meeting->transkrip()->delete();

        // Also delete any existing notulensi
        $meeting->notulensi()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Perekaman baru dimulai. Transkrip lama dibersihkan.',
        ]);
    }

    public function generateLiveNotulensi(Request $request, Meeting $meeting, GeminiNotulensiSummarizerService $summarizer)
    {
        $this->assertActiveParticipant($meeting);

        $transkrip = $meeting->transkrip;
        if (! $transkrip || blank($transkrip->hasil_transkrip)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum ada transkrip percakapan yang direkam. Silakan rekam percakapan terlebih dahulu.',
            ], 422);
        }

        try {
            $meeting->update([
                'pipeline_status' => MeetingPipelineStatus::Processing->value,
                'pipeline_stage' => MeetingPipelineStage::Summarize->value,
                'pipeline_error' => null,
                'pipeline_started_at' => now(),
            ]);

            $result = $summarizer->summarize($transkrip->hasil_transkrip);

            Log::info('generateLiveNotulensi: saving notulensi', [
                'meeting_id' => $meeting->id,
                'ringkasan_length' => strlen($result['ringkasan'] ?? ''),
            ]);

            $notulensi = \App\Models\Notulensi::query()->updateOrCreate(
                ['meeting_id' => $meeting->id],
                [
                    'ringkasan' => $result['ringkasan'],
                    'structured_summary' => $result['structured'],
                    'openai_model' => $result['model'],
                    'prompt_version' => GeminiNotulensiSummarizerService::PROMPT_VERSION,
                    'openai_usage' => $result['usage'],
                    'tanggal_generate' => now()->toDateString(),
                ]
            );

            Log::info('generateLiveNotulensi: notulensi saved', [
                'notulensi_id' => $notulensi->id,
                'exists_in_db' => $notulensi->exists,
            ]);

            // Generate PDF synchronously
            $relative = 'meetings/'.$meeting->id.'/notulensi-'.$notulensi->id.'.pdf';
            $absolute = Storage::disk('local')->path($relative);
            @mkdir(dirname($absolute), 0755, true);

            \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.notulensi', [
                'meeting' => $meeting,
                'notulensi' => $notulensi,
            ])->save($absolute);

            $notulensi->update([
                'file_pdf' => $relative,
            ]);

            \App\Models\Arsip::query()->updateOrCreate(
                ['meeting_id' => $meeting->id],
                [
                    'notulensi_id' => $notulensi->id,
                    'tanggal_arsip' => now()->toDateString(),
                ]
            );

            $meeting->update([
                'pipeline_status' => MeetingPipelineStatus::Completed->value,
                'pipeline_stage' => MeetingPipelineStage::GeneratePdf->value,
                'pipeline_error' => null,
                'pipeline_completed_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'notulensi' => $notulensi,
                'pdf_url' => route('meeting.notulensi.pdf', $meeting),
            ]);

        } catch (\Throwable $e) {
            Log::error('On-demand Gemini notulensi generation failed', [
                'meeting_id' => $meeting->id,
                'exception' => $e,
            ]);

            $meeting->markPipelineFailed(MeetingPipelineStage::Summarize->value, $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat notulensi dengan AI: '.$e->getMessage(),
            ], 500);
        }
    }

    public function showNotulensi(Meeting $meeting)
    {
        $this->assertMayAccessMeetingDocuments($meeting);

        $notulensi = $meeting->notulensi;
        abort_if(! $notulensi, 404, 'Notulensi belum tersedia untuk meeting ini.');

        return view('meeting.notulensi', compact('meeting', 'notulensi'));
    }

    public function downloadNotulensiPdf(Meeting $meeting)
    {
        $this->assertMayAccessMeetingDocuments($meeting);

        $notulensi = $meeting->notulensi;
        abort_if(! $notulensi || blank($notulensi->file_pdf), 404, 'PDF notulensi belum tersedia.');

        $disk = Storage::disk('local');
        abort_unless($disk->exists($notulensi->file_pdf), 404);

        $filename = 'notulensi-meeting-'.$meeting->id.'.pdf';

        return response()->download($disk->path($notulensi->file_pdf), $filename);
    }

    private function assertActiveParticipant(Meeting $meeting): void
    {
        $userId = (int) auth()->id();
        $isActive = $meeting->participants()
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->exists();

        abort_unless($isActive, 403, 'Anda tidak terdaftar sebagai peserta aktif.');
    }

    private function assertMayAccessMeetingDocuments(Meeting $meeting): void
    {
        $user = auth()->user();
        if ((int) $meeting->dibuat_oleh === (int) $user->id) {
            return;
        }

        if (Gate::forUser($user)->allows('view', $meeting)) {
            return;
        }

        $everJoined = $meeting->participants()
            ->where('user_id', $user->id)
            ->exists();

        abort_unless($everJoined, 403, 'Anda tidak memiliki akses dokumen meeting ini.');
    }

    private function enterMeeting(Meeting $meeting, int $userId): bool
    {
        $existing = $meeting->participants()
            ->where('user_id', $userId)
            ->first();

        if ($existing && $existing->left_at === null) {
            return true;
        }

        if ($meeting->activeParticipants()->count() >= self::MAX_PARTICIPANTS) {
            return false;
        }

        MeetingParticipant::query()->updateOrCreate(
            [
                'meeting_id' => $meeting->id,
                'user_id' => $userId,
            ],
            [
                'joined_at' => now(),
                'left_at' => null,
            ]
        );

        return true;
    }

    public function getLiveKitToken(Meeting $meeting)
    {
        $this->assertActiveParticipant($meeting);

        $user = auth()->user();
        $apiKey = config('livekit.api_key');
        $apiSecret = config('livekit.api_secret');
        $host = request()->getHost();
        $liveKitPort = parse_url(config('livekit.server_url'), PHP_URL_PORT) ?: '7880';
        $serverUrl = "ws://{$host}:{$liveKitPort}";

        $header = self::base64UrlEncode(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256',
        ]));

        $now = time();
        $payload = self::base64UrlEncode(json_encode([
            'iss' => $apiKey,
            'sub' => (string) $user->id,
            'exp' => $now + 3600,
            'nbf' => $now,
            'iat' => $now,
            'jti' => md5($user->id.'-'.$meeting->id.'-'.$now),
            'video' => [
                'room' => (string) $meeting->id,
                'roomJoin' => true,
                'roomCreate' => true,
                'roomList' => true,
                'roomRecord' => true,
                'canPublish' => true,
                'canSubscribe' => true,
                'canPublishData' => true,
            ],
            'name' => $user->name,
            'identity' => (string) $user->id,
            'metadata' => json_encode([
                'user_id' => $user->id,
                'name' => $user->name,
            ]),
        ]));

        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $apiSecret, true)
        );

        $token = "$header.$payload.$signature";

        return response()->json([
            'token' => $token,
            'serverUrl' => $serverUrl,
            'identity' => (string) $user->id,
            'room' => (string) $meeting->id,
        ]);
    }

    public function broadcastSignal(Request $request, Meeting $meeting)
    {
        $this->assertActiveParticipant($meeting);

        $validated = $request->validate([
            'type' => 'required|string|in:camera-toggle,start-recording-broadcast,stop-recording-broadcast,screen-share-start,screen-share-stop,screen-share-takeover',
            'isOff' => 'nullable|boolean',
            'name' => 'nullable|string|max:255',
        ]);

        $payload = array_merge($validated, [
            'sender_id' => auth()->id(),
            'sender_name' => (string) auth()->user()?->name,
            'sent_at' => now()->toIso8601String(),
        ]);

        broadcast(new \App\Events\WebRTCSignal($meeting->id, $payload))->toOthers();

        return response()->json(['status' => 'sent']);
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
