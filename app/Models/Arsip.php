<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'notulensi_id',
        'tanggal_arsip',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function notulensi()
    {
        return $this->belongsTo(Notulensi::class);
    }
}
