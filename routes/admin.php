<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\RekamanAudioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::middleware('user.permission:AdminAccessDashboard')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    });

    // Users
    Route::middleware('user.permission:AdminAccessUsers')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles
    Route::middleware('user.permission:AdminAccessRoles')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Meetings
    Route::middleware('user.permission:AdminAccessMeetings')->group(function () {
        Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
        Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    });

    // Agendas
    Route::middleware('user.permission:AdminAccessAgendas')->group(function () {
        Route::get('agendas', [AgendaController::class, 'index'])->name('agendas.index');
    });

    // Arsips
    Route::middleware('user.permission:AdminAccessArsips')->group(function () {
        Route::get('arsips', [ArsipController::class, 'index'])->name('arsips.index');
    });

    // Rekaman Audio
    Route::middleware('user.permission:AdminAccessRekamanAudio')->group(function () {
        Route::get('rekaman-audio', [RekamanAudioController::class, 'index'])->name('rekaman-audio.index');
        Route::delete('rekaman-audio/{rekaman}', [RekamanAudioController::class, 'destroy'])->name('rekaman-audio.destroy');
    });

    // Rekaman Video
    Route::middleware('user.permission:AdminAccessRekamanAudio')->group(function () {
        Route::get('rekaman-video', [RekamanAudioController::class, 'videoIndex'])->name('rekaman-video.index');
        Route::get('rekaman-video/{rekaman}/stream', [RekamanAudioController::class, 'stream'])->name('rekaman-video.stream');
        Route::get('rekaman-video/{rekaman}/download', [RekamanAudioController::class, 'download'])->name('rekaman-video.download');
        Route::delete('rekaman-video/{rekaman}', [RekamanAudioController::class, 'destroy'])->name('rekaman-video.destroy');
    });

});
