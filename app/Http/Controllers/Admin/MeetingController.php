<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with('creator')->latest()->paginate(20);
        return view('admin.meetings.index', compact('meetings'));
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['creator', 'participants.user', 'rekamanAudio', 'notulensi', 'agendas']);
        return view('admin.meetings.show', compact('meeting'));
    }
}
