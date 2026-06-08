<?php

namespace App\Jobs\LiveAudio;

use App\Models\LiveAudio;
use App\Services\GeminiNotulensiSummarizerService;
use App\Services\OpenAITranscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessLiveAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 3600;

    public function __construct(public int $liveAudioId)
    {
    }

    public function handle(
        OpenAITranscriptionService $transcriptionService,
        GeminiNotulensiSummarizerService $summarizerService
    ): void {
        $liveAudio = LiveAudio::query()->findOrFail($this->liveAudioId);

        Log::info('LiveAudio processing started', ['live_audio_id' => $liveAudio->id]);

        $relative = $liveAudio->file_path;

        if (blank($relative)) {
            throw new \RuntimeException('Path audio untuk transkripsi kosong.');
        }

        $absolute = Storage::disk('public')->path($relative);
        if (! is_file($absolute)) {
            throw new \RuntimeException('File audio untuk transkripsi tidak ditemukan.');
        }

        // 1. Transcribe Audio
        Log::info('LiveAudio transcribing...', ['absolute_path' => $absolute]);
        $transcribeResult = $transcriptionService->transcribeFile($absolute, $liveAudio->mime_type);
        $transcriptText = $transcribeResult['text'];

        if (blank($transcriptText)) {
            throw new \RuntimeException('Hasil transkripsi kosong, gagal melakukan perangkuman.');
        }

        // 2. Summarize Transcript
        Log::info('LiveAudio summarizing transcript...');
        $summaryResult = $summarizerService->summarize($transcriptText);

        // 3. Save as JSON
        $liveAudio->update([
            'notulensi_teks' => json_encode($summaryResult['structured'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);

        Log::info('LiveAudio processing completed successfully', ['live_audio_id' => $liveAudio->id]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('LiveAudio processing failed', [
            'live_audio_id' => $this->liveAudioId,
            'error' => $exception?->getMessage()
        ]);

        $liveAudio = LiveAudio::query()->find($this->liveAudioId);
        if ($liveAudio) {
            $liveAudio->update([
                'notulensi_teks' => json_encode([
                    'error' => 'Gagal memproses audio: ' . ($exception?->getMessage() ?? 'Unknown error')
                ]),
            ]);
        }
    }
}
