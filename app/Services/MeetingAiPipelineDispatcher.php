<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MeetingPipelineStage;
use App\Enums\MeetingPipelineStatus;
use App\Jobs\Meeting\ExtractAudioFromRecordingJob;
use App\Jobs\Meeting\GenerateNotulensiPdfJob;
use App\Jobs\Meeting\SummarizeTranscriptToNotulensiJob;
use App\Jobs\Meeting\TranscribeRecordingJob;
use App\Models\Meeting;
use App\Models\RekamanAudio;
use Illuminate\Support\Facades\Bus;

class MeetingAiPipelineDispatcher
{
    public static function startFromRekaman(RekamanAudio $rekaman): void
    {
        $meeting = $rekaman->meeting;
        $meetingId = $meeting->id;
        $rekamanId = $rekaman->id;

        $meeting->update([
            'pipeline_status' => MeetingPipelineStatus::Processing->value,
            'pipeline_stage' => MeetingPipelineStage::ExtractAudio->value,
            'pipeline_error' => null,
            'pipeline_started_at' => now(),
            'pipeline_completed_at' => null,
            'openai_usage_total' => null,
        ]);

        Bus::chain([
            new ExtractAudioFromRecordingJob($rekamanId),
            new TranscribeRecordingJob($rekamanId),
            new SummarizeTranscriptToNotulensiJob($meetingId),
            new GenerateNotulensiPdfJob($meetingId),
        ])->catch(function (\Throwable $e) use ($meetingId): void {
            Meeting::query()->whereKey($meetingId)->first()?->markPipelineFailed('chain', $e->getMessage());
        })->dispatch();
    }
}
