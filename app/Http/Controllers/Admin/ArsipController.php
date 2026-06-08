<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = Arsip::with(['meeting', 'notulensi'])->latest()->paginate(20);
        return view('admin.arsips.index', compact('arsips'));
    }
}
