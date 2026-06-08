<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveAudio extends Model
{
    use HasFactory;

    protected $table = 'live_audios';

    protected $fillable = [
        'user_id',
        'file_path',
        'durasi',
        'mime_type',
        'file_size_bytes',
        'tanggal_rekam',
        'notulensi_teks',
    ];

    protected $casts = [
        'tanggal_rekam' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
