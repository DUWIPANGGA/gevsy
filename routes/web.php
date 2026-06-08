<?php

use App\Events\WebRTCSignal;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\ProfileController;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('meeting.join.form');
});

// Custom Login Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user->hasAnyRole(['super_admin', 'admin'])) {
                return redirect('/admin');
            }

            return redirect()->intended('/join');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    });
});

Route::get('/logout', function (Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // ======================== PROFILE (semua user) ========================
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ======================== JOIN MEETING ========================
    Route::middleware('user.permission:JoinMeeting')->group(function () {
        Route::get('/join', [MeetingController::class, 'index'])
            ->name('meeting.join.form');

        Route::post('/join', [MeetingController::class, 'join'])
            ->name('meeting.join.submit');
    });

    // ======================== VIEW MEETING ROOM ========================
    Route::middleware('user.permission:ViewMeetingRoom')->group(function () {
        Route::get('/meeting/{id}', [MeetingController::class, 'room'])
            ->name('meeting.room');

        Route::post('/meeting/{meeting}/leave', [MeetingController::class, 'leave'])
            ->name('meeting.leave');

        Route::post('/meeting/{meeting}/livekit-token', [MeetingController::class, 'getLiveKitToken'])
            ->name('meeting.livekit-token');
    });

    // ======================== MANAGE MEETING RECORDING ========================
    Route::middleware('user.permission:ManageMeetingRecording')->group(function () {
        Route::post('/meeting/{meeting}/recording', [MeetingController::class, 'uploadRecording'])
            ->name('meeting.recording.upload');
    });

    // Screen recording upload (accessible to all meeting participants)
    Route::post('/meeting/{meeting}/upload-screen-recording', [MeetingController::class, 'uploadRecording'])
        ->name('meeting.upload-screen-recording');

    // ======================== MEETING BROADCAST ========================
    Route::middleware('user.permission:UseMeetingBroadcast')->group(function () {
        Route::post('/meeting/{meeting}/broadcast', [MeetingController::class, 'broadcastSignal'])
            ->name('meeting.broadcast');
    });

    // ======================== CREATE / UPDATE MEETING ========================
    Route::middleware('user.permission:CreateMeeting')->group(function () {
        Route::post('/meeting/create', [MeetingController::class, 'store'])
            ->name('meeting.create');
    });

    // ======================== AGENDA ========================
    Route::middleware('user.permission:AccessUserAgenda')->group(function () {
        Route::get('/agenda', [MeetingController::class, 'userAgenda'])
            ->name('meeting.agenda');
    });

    // ======================== LIVE TRANSCRIPTION & AI NOTULEN ========================
    Route::middleware('user.permission:UseLiveTranscription')->group(function () {
        Route::post('/meeting/{meeting}/save-live-transcript', [MeetingController::class, 'saveLiveTranscript'])
            ->name('meeting.save-live-transcript');

        Route::post('/meeting/{meeting}/start-recording', [MeetingController::class, 'startRecording'])
            ->name('meeting.start-recording');

        Route::post('/meeting/{meeting}/generate-notulensi', [MeetingController::class, 'generateLiveNotulensi'])
            ->name('meeting.generate-notulensi');
    });

    // ======================== NOTULENSI (view & download) ========================
    Route::get('/meeting/{meeting}/notulensi', [MeetingController::class, 'showNotulensi'])
        ->middleware('user.permission:AccessUserNotulensi')
        ->name('meeting.notulensi.show');

    Route::get('/meeting/{meeting}/notulensi-pdf', [MeetingController::class, 'downloadNotulensiPdf'])
        ->middleware('user.permission:DownloadUserNotulensi')
        ->name('meeting.notulensi.pdf');

    Route::get('/meeting/{meeting}/pipeline-status', [MeetingController::class, 'pipelineStatus'])
        ->middleware(['user.permission:AccessUserNotulensi', 'throttle:120,1'])
        ->name('meeting.pipeline.status');

    // ======================== AUDIO NOTULENSI ========================
    Route::middleware('user.permission:AccessUserAudio')->group(function () {
        Route::get('/audio', [AudioController::class, 'index'])->name('audio.index');
        Route::get('/audio/history', [AudioController::class, 'history'])->name('audio.history');
        Route::get('/audio/{liveAudio}', [AudioController::class, 'show'])->name('audio.show');
        Route::get('/audio/{liveAudio}/pdf', [AudioController::class, 'downloadPdf'])->name('audio.pdf');
        Route::get('/audio-notulensi/whisper-url', [AudioController::class, 'whisperUrl'])->name('audio.whisper-url');
    });

    Route::middleware('user.permission:EditUserAudio')->group(function () {
        Route::get('/audio/{liveAudio}/edit', [AudioController::class, 'edit'])->name('audio.edit');
        Route::put('/audio/{liveAudio}', [AudioController::class, 'update'])->name('audio.update');
    });

    Route::middleware('user.permission:DeleteUserAudio')->group(function () {
        Route::delete('/audio/{liveAudio}', [AudioController::class, 'destroy'])
            ->name('audio.destroy');
    });

    Route::middleware('user.permission:CreateUserAudio')->group(function () {
        Route::post('/audio-notulensi/upload', [AudioController::class, 'upload'])->name('audio.upload');
        Route::post('/audio-notulensi/save', [AudioController::class, 'save'])->name('audio.save');
        Route::post('/audio-notulensi/save-raw', [AudioController::class, 'saveRaw'])->name('audio.save-raw');
    });

    // ======================== VIDEO REKAMAN LAYAR ========================
    Route::prefix('videos')->group(function () {
        Route::get('/', [\App\Http\Controllers\VideoController::class, 'index'])->name('video.index');
        Route::get('/{rekaman}', [\App\Http\Controllers\VideoController::class, 'show'])->name('video.show');
        Route::get('/{rekaman}/stream', [\App\Http\Controllers\VideoController::class, 'stream'])->name('video.stream');
        Route::get('/{rekaman}/download', [\App\Http\Controllers\VideoController::class, 'download'])->name('video.download');
        Route::delete('/{rekaman}', [\App\Http\Controllers\VideoController::class, 'destroy'])->name('video.destroy');
    });
});

require __DIR__.'/admin.php';
