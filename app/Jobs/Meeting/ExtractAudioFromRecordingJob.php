<?php

declare(strict_types=1);

namespace App\Jobs\Meeting;

use App\Enums\MeetingPipelineStage;
use App\Models\Meeting;
use App\Models\RekamanAudio;
use App\Services\AudioExtractionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ExtractAudioFromRecordingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 3600;

    public function __construct(public int $rekamanAudioId) {}

    public function handle(AudioExtractionService $extractor): void
    {
        $rekaman = RekamanAudio::query()->findOrFail($this->rekamanAudioId);
        $meeting = $rekaman->meeting;
        Log::info('meeting_pipeline.extract_audio.start', [
            'meeting_id' => $meeting->id,
            'rekaman_audio_id' => $rekaman->id,
        ]);
        $meeting->update(['pipeline_stage' => MeetingPipelineStage::ExtractAudio->value]);

        $raw = $rekaman->raw_recording_path ?? $rekaman->file_audio;
        if (blank($raw)) {
            throw new \RuntimeException('Rekaman tidak memiliki path file.');
        }

        $absRaw = Storage::disk('local')->path($raw);
        if (! is_file($absRaw)) {
            throw new \RuntimeException('File rekaman tidak ditemukan di storage.');
        }

        $outRel = 'meetings/'.$meeting->id.'/extracted-'.uniqid('', true).'.mp3';
        $absOut = Storage::disk('local')->path($outRel);

        if ($extractor->extractToMp3($absRaw, $absOut)) {
            $rekaman->update([
                'extracted_audio_path' => $outRel,
                'file_audio' => $outRel,
            ]);
        } else {
            $rekaman->update([
                'extracted_audio_path' => null,
                'file_audio' => $raw,
            ]);
        }
    }

    public function failed(?Throwable $exception): void
    {
        $rekaman = RekamanAudio::query()->find($this->rekamanAudioId);
        if ($rekaman) {
            $rekaman->update([
                'processing_error' => $exception?->getMessage() ?? 'extract_failed',
            ]);
            $rekaman->meeting->markPipelineFailed(
                MeetingPipelineStage::ExtractAudio->value,
                $exception?->getMessage() ?? 'extract_failed'
            );
        }
    }
}
