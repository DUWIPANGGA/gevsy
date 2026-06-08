<?php

declare(strict_types=1);

namespace App\Jobs\Meeting;

use App\Enums\MeetingPipelineStage;
use App\Models\RekamanAudio;
use App\Models\Transkrip;
use App\Services\OpenAITranscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TranscribeRecordingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 3600;

    public function __construct(public int $rekamanAudioId) {}

    public function handle(OpenAITranscriptionService $transcription): void
    {
        $rekaman = RekamanAudio::query()->findOrFail($this->rekamanAudioId);
        $meeting = $rekaman->meeting;
        Log::info('meeting_pipeline.transcribe.start', [
            'meeting_id' => $meeting->id,
            'rekaman_audio_id' => $rekaman->id,
        ]);
        $meeting->update(['pipeline_stage' => MeetingPipelineStage::Transcribe->value]);

        $relative = $rekaman->extracted_audio_path
            ?? $rekaman->raw_recording_path
            ?? $rekaman->file_audio;

        if (blank($relative)) {
            throw new \RuntimeException('Path audio untuk transkripsi kosong.');
        }

        $absolute = Storage::disk('local')->path($relative);
        if (! is_file($absolute)) {
            throw new \RuntimeException('File audio untuk transkripsi tidak ditemukan.');
        }

        $result = $transcription->transcribeFile($absolute, $rekaman->mime_type);

        Transkrip::query()->updateOrCreate(
            ['meeting_id' => $meeting->id],
            [
                'hasil_transkrip' => $result['text'],
                'openai_model' => $result['model'],
                'openai_usage' => $result['usage'],
                'tanggal_generate' => now()->toDateString(),
            ]
        );

        $meeting->mergeOpenAiUsage($this->normalizeUsage($result['usage']));
    }

    /**
     * @param  array<string, mixed>  $usage
     * @return array<string, int>
     */
    private function normalizeUsage(array $usage): array
    {
        $out = [];
        foreach (['prompt_tokens', 'completion_tokens', 'total_tokens'] as $k) {
            if (array_key_exists($k, $usage)) {
                $out[$k] = (int) $usage[$k];
            }
        }

        return $out;
    }

    public function failed(?Throwable $exception): void
    {
        $rekaman = RekamanAudio::query()->find($this->rekamanAudioId);
        if ($rekaman) {
            $rekaman->update([
                'processing_error' => $exception?->getMessage() ?? 'transcribe_failed',
            ]);
            $rekaman->meeting->markPipelineFailed(
                MeetingPipelineStage::Transcribe->value,
                $exception?->getMessage() ?? 'transcribe_failed'
            );
        }
    }
}
