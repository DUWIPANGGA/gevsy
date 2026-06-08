<?php

namespace App\Http\Controllers;

use App\Models\MeetingParticipant;
use App\Models\RekamanAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $meetingIds = MeetingParticipant::where('user_id', $userId)
            ->pluck('meeting_id')
            ->merge(
                \App\Models\Meeting::where('dibuat_oleh', $userId)->pluck('id')
            )
            ->unique();

        $videos = RekamanAudio::where('tipe_rekaman', 'video')
            ->whereIn('meeting_id', $meetingIds)
            ->with('meeting')
            ->latest()
            ->paginate(20);

        return view('video.index', compact('videos'));
    }

    public function show(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        if ($rekaman->tipe_rekaman !== 'video') {
            abort(404);
        }

        $rekaman->load('meeting.notulensi');

        return view('video.show', compact('rekaman'));
    }

    public function stream(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        $disk = Storage::disk('local');
        $path = $rekaman->raw_recording_path;

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $rekaman->mime_type ?: 'video/webm';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
        ]);
    }

    public function download(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        $disk = Storage::disk('local');
        $path = $rekaman->raw_recording_path;

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $rekaman->mime_type ?: 'video/webm';
        $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'webm';
        $filename = 'rekaman-' . ($rekaman->meeting_id ?? 'unknown') . '-' . $rekaman->id . '.' . $ext;

        return response()->download($fullPath, $filename, [
            'Content-Type' => $mime,
        ]);
    }

    public function destroy(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        $disk = Storage::disk('local');
        if ($rekaman->raw_recording_path && $disk->exists($rekaman->raw_recording_path)) {
            $disk->delete($rekaman->raw_recording_path);
        }

        $rekaman->delete();

        return redirect()->route('video.index')->with('success', 'Video berhasil dihapus.');
    }

    private function authorizeAccess(RekamanAudio $rekaman)
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }

        $meeting = $rekaman->meeting;

        if (!$meeting) {
            abort(403, 'Meeting tidak ditemukan.');
        }

        $isCreator = $meeting->dibuat_oleh === $user->id;
        $isParticipant = MeetingParticipant::where('meeting_id', $meeting->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isCreator && !$isParticipant) {
            abort(403, 'Anda tidak memiliki akses ke rekaman ini.');
        }
    }
}
