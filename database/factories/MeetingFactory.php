<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meeting>
 */
class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        return [
            'nama_rapat' => fake()->sentence(3),
            'deskripsi_rapat' => fake()->optional()->paragraph(),
            'tanggal' => now()->toDateString(),
            'waktu' => now()->format('H:i'),
            'tipe_rapat' => 'internal',
            'link_meeting' => null,
            'password_rapat' => null,
            'dibuat_oleh' => User::factory(),
            'status_rapat' => 'dijadwalkan',
            'pipeline_status' => 'idle',
            'pipeline_stage' => null,
            'pipeline_error' => null,
            'pipeline_started_at' => null,
            'pipeline_completed_at' => null,
            'openai_usage_total' => null,
        ];
    }
}
