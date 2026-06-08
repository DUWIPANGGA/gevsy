<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    /** @use HasFactory<\Database\Factories\MeetingFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_rapat',
        'deskripsi_rapat',
        'tanggal',
        'waktu',
        'tipe_rapat',
        'link_meeting',
        'password_rapat',
        'dibuat_oleh',
        'status_rapat',
        'pipeline_status',
        'pipeline_stage',
        'pipeline_error',
        'pipeline_started_at',
        'pipeline_completed_at',
        'openai_usage_total',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'pipeline_started_at' => 'datetime',
            'pipeline_completed_at' => 'datetime',
            'openai_usage_total' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Meeting $meeting): void {
            if ($meeting->tipe_rapat === 'Offline') {
                return;
            }
            if (blank($meeting->link_meeting)) {
                $meeting->updateQuietly([
                    'link_meeting' => route('meeting.room', $meeting->id),
                ]);
            }
        });
    }

    public function isOnline(): bool
    {
        return $this->tipe_rapat === 'Online';
    }

    public function isOffline(): bool
    {
        return $this->tipe_rapat === 'Offline';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }

    public function rekamanAudio()
    {
        return $this->hasMany(RekamanAudio::class);
    }

    public function transkrip()
    {
        return $this->hasOne(Transkrip::class);
    }

    public function notulensi()
    {
        return $this->hasOne(Notulensi::class);
    }

    public function arsip()
    {
        return $this->hasOne(Arsip::class);
    }

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function activeParticipants()
    {
        return $this->participants()->whereNull('left_at');
    }

    public function getGeneratedLinkMeetingAttribute(): string
    {
        return route('meeting.room', $this->id);
    }

    public function mergeOpenAiUsage(?array $usage): void
    {
        if ($usage === null || $usage === []) {
            return;
        }

        $current = $this->openai_usage_total ?? [];
        foreach (['prompt_tokens', 'completion_tokens', 'total_tokens'] as $key) {
            if (! array_key_exists($key, $usage)) {
                continue;
            }
            $current[$key] = (int) ($current[$key] ?? 0) + (int) $usage[$key];
        }

        $this->openai_usage_total = $current;
        $this->save();
    }

    public function markPipelineFailed(string $stage, string $message): void
    {
        $this->update([
            'pipeline_status' => \App\Enums\MeetingPipelineStatus::Failed->value,
            'pipeline_stage' => $stage,
            'pipeline_error' => $message,
        ]);
    }
}
