<?php

declare(strict_types=1);

namespace App\Jobs\Meeting;

use App\Enums\MeetingPipelineStage;
use App\Enums\MeetingPipelineStatus;
use App\Models\Arsip;
use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateNotulensiPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 600;

    public function __construct(public int $meetingId) {}

    public function handle(): void
    {
        $meeting = Meeting::query()->findOrFail($this->meetingId);
        Log::info('meeting_pipeline.generate_pdf.start', [
            'meeting_id' => $meeting->id,
        ]);
        $meeting->update(['pipeline_stage' => MeetingPipelineStage::GeneratePdf->value]);

        $notulensi = $meeting->notulensi;
        if (! $notulensi) {
            throw new \RuntimeException('Notulensi belum tersedia.');
        }

        $relative = 'meetings/'.$meeting->id.'/notulensi-'.$notulensi->id.'.pdf';
        $absolute = Storage::disk('local')->path($relative);

        @mkdir(dirname($absolute), 0755, true);

        Pdf::loadView('pdf.notulensi', [
            'meeting' => $meeting,
            'notulensi' => $notulensi,
        ])->save($absolute);

        $notulensi->update([
            'file_pdf' => $relative,
        ]);

        Arsip::query()->updateOrCreate(
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
    }

    public function failed(?Throwable $exception): void
    {
        Meeting::query()->whereKey($this->meetingId)->first()?->markPipelineFailed(
            MeetingPipelineStage::GeneratePdf->value,
            $exception?->getMessage() ?? 'pdf_failed'
        );
    }
}
