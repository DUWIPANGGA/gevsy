<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transkrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'hasil_transkrip',
        'openai_model',
        'openai_usage',
        'tanggal_generate',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_generate' => 'date',
            'openai_usage' => 'array',
        ];
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
