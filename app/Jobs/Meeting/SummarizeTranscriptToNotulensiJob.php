<?php

declare(strict_types=1);

namespace App\Jobs\Meeting;

use App\Enums\MeetingPipelineStage;
use App\Models\Meeting;
use App\Models\Notulensi;
use App\Services\OpenAINotulensiSummarizerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SummarizeTranscriptToNotulensiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 600;

    public function __construct(public int $meetingId) {}

    public function handle(OpenAINotulensiSummarizerService $summarizer): void
    {
        $meeting = Meeting::query()->findOrFail($this->meetingId);
        Log::info('meeting_pipeline.summarize.start', [
            'meeting_id' => $meeting->id,
        ]);
        $meeting->update(['pipeline_stage' => MeetingPipelineStage::Summarize->value]);

        $transkrip = $meeting->transkrip;
        if (! $transkrip || blank($transkrip->hasil_transkrip)) {
            throw new \RuntimeException('Transkrip belum tersedia untuk meeting ini.');
        }

        $result = $summarizer->summarize($transkrip->hasil_transkrip);

        Notulensi::query()->updateOrCreate(
            ['meeting_id' => $meeting->id],
            [
                'ringkasan' => $result['ringkasan'],
                'structured_summary' => $result['structured'],
                'openai_model' => $result['model'],
                'prompt_version' => OpenAINotulensiSummarizerService::PROMPT_VERSION,
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
        Meeting::query()->whereKey($this->meetingId)->first()?->markPipelineFailed(
            MeetingPipelineStage::Summarize->value,
            $exception?->getMessage() ?? 'summarize_failed'
        );
    }
}
