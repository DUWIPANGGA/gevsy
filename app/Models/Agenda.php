<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'tanggal',
        'waktu',
        'nama_rapat',
        'tipe_agenda',
        'keterangan',
        'meeting_id',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
