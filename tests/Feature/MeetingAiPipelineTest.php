<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\MeetingPipelineStatus;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MeetingAiPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_recording_runs_pipeline_and_produces_transcript_notulensi_and_pdf(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite tidak tersedia di environment ini.');
        }

        Storage::fake('local');

        Http::fake(function (\Illuminate\Http\Client\Request $request) {
            $url = $request->url();
            if (str_contains($url, 'audio/transcriptions')) {
                return Http::response([
                    'text' => 'Ini adalah percakapan rapat singkat untuk pengujian.',
                    'usage' => ['prompt_tokens' => 0, 'completion_tokens' => 0, 'total_tokens' => 12],
                ], 200);
            }
            if (str_contains($url, 'chat/completions')) {
                $payload = [
                    'ringkasan' => 'Rapat membahas pengujian pipeline.',
                    'topik_dibahas' => ['Pengujian'],
                    'keputusan' => ['Lanjutkan pengembangan'],
                    'action_items' => [
                        ['task' => 'Verifikasi PDF', 'pic' => 'Tim QA', 'deadline' => 'Besok'],
                    ],
                    'risiko_catatan' => [],
                ];

                return Http::response([
                    'choices' => [
                        [
                            'message' => [
                                'content' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                            ],
                        ],
                    ],
                    'usage' => [
                        'prompt_tokens' => 5,
                        'completion_tokens' => 7,
                        'total_tokens' => 12,
                    ],
                ], 200);
            }

            return Http::response(['error' => 'unexpected url: '.$url], 500);
        });

        $user = User::factory()->create();
        $meeting = Meeting::factory()->create([
            'dibuat_oleh' => $user->id,
        ]);

        MeetingParticipant::query()->create([
            'meeting_id' => $meeting->id,
            'user_id' => $user->id,
            'joined_at' => now(),
            'left_at' => null,
        ]);

        $file = UploadedFile::fake()->create('recording.webm', 32, 'video/webm');

        $this->actingAs($user)
            ->post(route('meeting.recording.upload', $meeting), [
                'recording' => $file,
                'duration_seconds' => 42,
            ])
            ->assertOk()
            ->assertJsonFragment(['status' => 'queued']);

        $meeting->refresh();

        $this->assertSame(MeetingPipelineStatus::Completed->value, $meeting->pipeline_status);
        $this->assertDatabaseHas('transkrips', [
            'meeting_id' => $meeting->id,
        ]);
        $this->assertDatabaseHas('notulensis', [
            'meeting_id' => $meeting->id,
        ]);

        $notulensi = $meeting->fresh()->notulensi;
        $this->assertNotNull($notulensi);
        $this->assertNotEmpty($notulensi->file_pdf);
        Storage::disk('local')->assertExists($notulensi->file_pdf);

        $this->actingAs($user)
            ->get(route('meeting.notulensi.pdf', $meeting))
            ->assertOk();
    }
}
