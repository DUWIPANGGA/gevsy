@extends('layouts.app')

@section('content')
<div class="p-6 w-full max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('video.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-2 transition" style="color:var(--text-secondary)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold tracking-tight" style="color:var(--text-primary)">{{ $rekaman->meeting?->nama_rapat ?? 'Rekaman Video' }}</h1>
            <p class="mt-1 text-sm" style="color:var(--text-secondary)">
                {{ $rekaman->tanggal_upload ? \Carbon\Carbon::parse($rekaman->tanggal_upload)->translatedFormat('d F Y H:i') : $rekaman->created_at->translatedFormat('d F Y H:i') }}
                &middot; {{ $rekaman->durasi ?? '-' }}
                @if($rekaman->file_size_bytes)
                    &middot; {{ number_format($rekaman->file_size_bytes / 1024 / 1024, 1) }} MB
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('video.download', $rekaman->id) }}"
               class="inline-flex items-center gap-2 font-semibold py-2.5 px-5 rounded-lg transition shadow-lg hover:-translate-y-0.5" style="background:linear-gradient(135deg, #7c3aed, #4f46e5);color:#fff" download>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download
            </a>
            <form action="{{ route('video.destroy', $rekaman->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekaman video ini?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 font-semibold py-2.5 px-5 rounded-lg transition" style="background:rgba(239,68,68,0.1);color:#ef4444">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Video Player --}}
    <div class="card overflow-hidden">
        <div class="bg-black" style="position:relative;padding-bottom:56.25%">
            <video
                id="videoPlayer"
                controls
                autoplay
                preload="auto"
                style="position:absolute;top:0;left:0;width:100%;height:100%;outline:none"
                style="color:var(--text-primary)"
            >
                <source src="{{ route('video.stream', $rekaman->id) }}" type="{{ $rekaman->mime_type ?: 'video/webm' }}">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
        <div class="px-5 py-4 flex items-center justify-between" style="border-top:1px solid var(--divider)">
            <div class="flex items-center gap-4 text-sm" style="color:var(--text-secondary)">
                <span>Tipe: {{ strtoupper($rekaman->mime_type ?: 'WEBM') }}</span>
                @if($rekaman->duration_seconds)
                <span>&middot; {{ gmdate('H:i:s', $rekaman->duration_seconds) }}</span>
                @endif
            </div>
            @if($rekaman->meeting)
            <a href="{{ route('meeting.room', $rekaman->meeting_id) }}" class="text-sm font-medium transition" style="color:#7c3aed">
                Buka Meeting &rarr;
            </a>
            @endif
        </div>
    </div>

    {{-- Notulensi --}}
    @if($rekaman->meeting && $rekaman->meeting->notulensi)
    <div class="mt-8">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <h2 class="text-lg font-bold" style="color:var(--text-primary)">Notulensi Rapat</h2>
            <a href="{{ route('meeting.notulensi.pdf', $rekaman->meeting_id) }}" target="_blank"
               class="ml-auto inline-flex items-center gap-2 text-white font-medium py-2 px-4 rounded-lg transition text-sm shadow-lg shadow-violet-500/20" style="background:linear-gradient(135deg, #7c3aed, #6366f1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Unduh PDF
            </a>
        </div>
        <x-notulensi-card :notulensi="$rekaman->meeting->notulensi" />
    </div>
    @endif
</div>
@endsection
