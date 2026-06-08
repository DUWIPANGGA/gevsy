@extends('layouts.app')

@section('content')
<div class="p-6 w-full max-w-5xl mx-auto">
    {{-- Back Button --}}
    <a href="{{ route('audio.history') }}" class="inline-flex items-center gap-2 text-sm mb-6 transition group" style="color:var(--text-secondary)">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Riwayat
    </a>

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold" style="color:var(--text-primary)">Detail Notulensi</h1>
            <p class="mt-1" style="color:var(--text-secondary)">
                Direkam pada {{ $liveAudio->tanggal_rekam ? $liveAudio->tanggal_rekam->format('d F Y, H:i') : $liveAudio->created_at->format('d F Y, H:i') }} WIB
            </p>
        </div>
        @if($notulensi)
        <div class="flex items-center gap-3">
            @can('edit_notulensi')
            <a href="{{ route('audio.edit', $liveAudio->id) }}"
               class="inline-flex items-center gap-2 font-medium py-2.5 px-4 rounded-lg transition text-sm surface-card" style="color:var(--text-secondary)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @endcan
            <a href="{{ route('audio.pdf', $liveAudio->id) }}" target="_blank"
               class="inline-flex items-center gap-2 text-white font-medium py-2.5 px-4 rounded-lg transition text-sm shadow-lg shadow-violet-500/20" style="background:linear-gradient(135deg, #7c3aed, #6366f1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Unduh PDF
            </a>
        </div>
        @endif
    </div>

    {{-- Session Alert --}}
    @if (session('success'))
        <div class="surface-card px-4 py-3 rounded-xl mb-6 flex items-center gap-3" style="border-color:rgba(34,197,94,0.3);color:#16a34a">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- File Info Card --}}
    <div class="page-card p-6 mb-6">
        <h2 class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:var(--text-muted)">Informasi File Rekaman</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-xl p-4 surface-card">
                <p class="text-xs mb-1" style="color:var(--text-muted)">Tanggal Rekam</p>
                <p class="text-sm font-semibold" style="color:var(--text-primary)">{{ $liveAudio->tanggal_rekam?->format('d M Y') ?? '-' }}</p>
            </div>
            <div class="rounded-xl p-4 surface-card">
                <p class="text-xs mb-1" style="color:var(--text-muted)">Waktu</p>
                <p class="text-sm font-semibold" style="color:var(--text-primary)">{{ $liveAudio->tanggal_rekam?->format('H:i') ?? '-' }} WIB</p>
            </div>
            <div class="rounded-xl p-4 surface-card">
                <p class="text-xs mb-1" style="color:var(--text-muted)">Ukuran File</p>
                <p class="text-sm font-semibold" style="color:var(--text-primary)">{{ number_format($liveAudio->file_size_bytes / 1024 / 1024, 2) }} MB</p>
            </div>
            <div class="rounded-xl p-4 surface-card">
                <p class="text-xs mb-1" style="color:var(--text-muted)">Status AI</p>
                @if($notulensi && !isset($notulensi['error']))
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Gemini Selesai
                    </span>
                @elseif($notulensi && isset($notulensi['error']))
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Error
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-sm font-semibold animate-pulse" style="color:var(--text-secondary)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Memproses...
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Audio Player Card --}}
    @if($liveAudio->file_path)
    <div class="page-card p-6 mb-6">
        <h2 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:var(--text-muted)">Putar Rekaman Audio</h2>
        <audio controls class="w-full rounded-lg">
            <source src="{{ asset('storage/' . $liveAudio->file_path) }}" type="{{ $liveAudio->mime_type ?? 'audio/ogg' }}">
            Browser Anda tidak mendukung pemutar audio.
        </audio>
        <style>
            audio::-webkit-media-controls-panel { background: var(--surface-bg); }
            audio::-webkit-media-controls-current-time-display,
            audio::-webkit-media-controls-time-remaining-display { color: var(--text-primary); }
        </style>
    </div>
    @endif

    {{-- Error from AI --}}
    @if($notulensi && isset($notulensi['error']))
        <div class="rounded-2xl p-6 mb-5 surface-card" style="border-color:rgba(239,68,68,0.2)">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold mb-1" style="color:var(--text-primary)">AI Gagal Memproses</p>
                    <p class="text-sm" style="color:var(--text-secondary)">{{ $notulensi['error'] }}</p>
                </div>
            </div>
        </div>

    @elseif($notulensi)
        <x-notulensi-card :notulensi="$notulensi" />

    @else
        {{-- Notulensi belum tersedia --}}
        <div class="rounded-2xl p-8 text-center surface-card" style="border-color:rgba(234,179,8,0.2)">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:rgba(234,179,8,0.1)">
                <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <h3 class="text-lg font-semibold mb-2" style="color:var(--text-primary)">Gemini AI Masih Memproses</h3>
            <p class="text-sm max-w-sm mx-auto" style="color:var(--text-secondary)">Notulensi akan muncul otomatis setelah Gemini selesai menganalisis transkrip audio. Silakan cek kembali beberapa saat lagi.</p>
            <a href="{{ route('audio.show', $liveAudio->id) }}" class="inline-flex items-center gap-2 mt-5 text-sm font-medium transition" style="color:var(--text-secondary)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh Halaman
            </a>
        </div>
    @endif
</div>
@endsection