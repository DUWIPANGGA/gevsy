<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;

class AgendaController extends Controller
{
    public function index()
    {
        $meetings = Meeting::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->get();

        return view('admin.agendas.index', compact('meetings'));
    }
}
