<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamanAudio extends Model
{
    use HasFactory;

    protected $table = 'rekaman_audio';

    protected $fillable = [
        'meeting_id',
        'file_audio',
        'raw_recording_path',
        'extracted_audio_path',
        'mime_type',
        'file_size_bytes',
        'duration_seconds',
        'language',
        'processing_error',
        'durasi',
        'tanggal_upload',
        'tipe_rekaman',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_upload' => 'date',
        ];
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
