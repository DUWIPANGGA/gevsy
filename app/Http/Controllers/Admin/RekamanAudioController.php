<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamanAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekamanAudioController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamanAudio::with('meeting');

        if ($request->filled('tipe')) {
            $query->where('tipe_rekaman', $request->tipe);
        }

        $rekamans = $query->latest()->paginate(20)->withQueryString();
        $tipe = $request->tipe;

        return view('admin.rekaman-audio.index', compact('rekamans', 'tipe'));
    }

    public function stream(RekamanAudio $rekaman)
    {
        if ($rekaman->tipe_rekaman !== 'video') {
            abort(404);
        }

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
        if ($rekaman->tipe_rekaman !== 'video') {
            abort(404);
        }

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
        $disk = Storage::disk('local');
        if ($rekaman->raw_recording_path && $disk->exists($rekaman->raw_recording_path)) {
            $disk->delete($rekaman->raw_recording_path);
        }

        $rekaman->delete();

        return redirect()->route('admin.rekaman-audio.index')
            ->with('success', 'Rekaman berhasil dihapus.');
    }
}
