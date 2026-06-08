<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Notulensi;
use App\Models\RekamanAudio;
use App\Models\Transkrip;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_meetings'   => Meeting::count(),
            'total_online'     => Meeting::where('tipe_rapat', 'Online')->count(),
            'total_offline'    => Meeting::where('tipe_rapat', 'Offline')->count(),
            'total_rekaman'    => RekamanAudio::count(),
            'total_transkripsi'=> Transkrip::count(),
            'total_notulensi'  => Notulensi::count(),
        ];

        $recentMeetings = Meeting::latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentMeetings'));
    }
}
