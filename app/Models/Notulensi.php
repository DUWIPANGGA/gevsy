<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notulensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'live_audio_id',
        'ringkasan',
        'structured_summary',
        'openai_model',
        'prompt_version',
        'openai_usage',
        'file_pdf',
        'tanggal_generate',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_generate' => 'date',
            'structured_summary' => 'array',
            'openai_usage' => 'array',
        ];
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function liveAudio()
    {
        return $this->belongsTo(LiveAudio::class);
    }

    public function arsip()
    {
        return $this->hasOne(Arsip::class);
    }
}
