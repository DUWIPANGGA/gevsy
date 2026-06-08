@extends('layouts.app')

@section('content')
<div class="p-6 w-full max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Riwayat Notulensi</h1>
            <p class="mt-1.5" style="color:var(--text-secondary)">Kelola riwayat rekaman dan hasil transkripsi AI Anda.</p>
        </div>
        <a href="{{ route('audio.index') }}"
           class="flex items-center gap-2 font-semibold py-2.5 px-5 rounded-lg transition-all duration-200 shadow-lg shadow-violet-500/20 hover:-translate-y-0.5" style="background:linear-gradient(135deg, #7c3aed, #4f46e5);color:#fff">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Rekam Baru
        </a>
    </div>

    {{-- Success / Error Alert --}}
    @if (session('success'))
        <div class="surface-card px-4 py-3 rounded-xl mb-6 flex items-center gap-3" style="border-color:rgba(34,197,94,0.3);color:#16a34a">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="surface-card px-4 py-3 rounded-xl mb-6 flex items-center gap-3" style="border-color:rgba(239,68,68,0.3);color:#dc2626">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Cards Grid --}}
    @if($audios->isEmpty())
        <div class="page-card flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4" style="background:rgba(139,92,246,0.08)">
                <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-1" style="color:var(--text-secondary)">Belum Ada Riwayat Notulensi</h3>
            <p class="text-sm mb-6 max-w-sm" style="color:var(--text-muted)">Mulai rekam rapat Anda dan AI kami akan otomatis membuat notulensi yang rapi dan terstruktur.</p>
            <a href="{{ route('audio.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-lg shadow-violet-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Rekam Sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($audios as $audio)
                @php
                    $notulensi = $audio->notulensi_teks ? json_decode($audio->notulensi_teks, true) : null;
                    // Hanya valid jika tidak ada key 'error' dari AI
                    $hasNotulensi = !empty($notulensi) && !isset($notulensi['error']);
                    // Gunakan key Gemini: "topik_dibahas"
                    $topik = $hasNotulensi ? ($notulensi['topik_dibahas'] ?? null) : null;
                    $topikText = is_array($topik) && count($topik)
                        ? implode(' · ', array_slice($topik, 0, 2))
                        : ($hasNotulensi ? 'Notulensi siap ditampilkan' : null);
                @endphp
                <div class="page-card flex flex-col overflow-hidden group">
                    {{-- Card Header --}}
                    <div class="px-5 py-4 flex items-center justify-between" style="background:linear-gradient(135deg, #7c3aed, #4f46e5);color:#fff">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium">Rekaman Audio</p>
                                <p class="font-semibold text-sm">{{ $audio->tanggal_rekam ? $audio->tanggal_rekam->format('d M Y') : $audio->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if($hasNotulensi)
                            <span class="bg-green-400/30 text-green-100 text-xs font-medium py-1 px-2.5 rounded-full border border-green-300/40">✓ Gemini Selesai</span>
                        @elseif(!empty($notulensi) && isset($notulensi['error']))
                            <span class="bg-red-400/30 text-red-100 text-xs font-medium py-1 px-2.5 rounded-full border border-red-300/40">✗ Error AI</span>
                        @else
                            <span class="bg-yellow-400/30 text-yellow-100 text-xs font-medium py-1 px-2.5 rounded-full border border-yellow-300/40 animate-pulse">⏳ Memproses</span>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="flex-1 px-5 py-4">
                        <div class="space-y-2.5">
                            {{-- Time --}}
                            <div class="flex items-center gap-2 text-sm" style="color:var(--text-secondary)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $audio->tanggal_rekam ? $audio->tanggal_rekam->format('H:i') : $audio->created_at->format('H:i') }} WIB &middot; {{ $audio->created_at->diffForHumans() }}</span>
                            </div>
                            {{-- File size --}}
                            <div class="flex items-center gap-2 text-sm" style="color:var(--text-secondary)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>{{ number_format($audio->file_size_bytes / 1024 / 1024, 2) }} MB</span>
                            </div>
                            {{-- Topik preview --}}
                            @if($hasNotulensi && $topikText)
                            <div class="mt-3 rounded-lg p-3 border surface-card" style="border-color:rgba(139,92,246,0.15)">
                                <p class="text-xs font-semibold uppercase tracking-wide mb-1 text-violet-600">Topik Dibahas</p>
                                <p class="text-sm line-clamp-2" style="color:var(--text-secondary)">{{ $topikText }}</p>
                            </div>
                            @elseif(!empty($notulensi) && isset($notulensi['error']))
                            <div class="mt-3 rounded-lg p-3 border" style="background:rgba(239,68,68,0.05);border-color:rgba(239,68,68,0.15)">
                                <p class="text-xs font-semibold text-red-600 mb-1">Gemini AI Error</p>
                                <p class="text-xs" style="color:var(--text-secondary)">{{ $notulensi['error'] }}</p>
                            </div>
                            @elseif(!$hasNotulensi)
                            <div class="mt-3 rounded-lg p-3 border" style="background:rgba(234,179,8,0.05);border-color:rgba(234,179,8,0.15)">
                                <p class="text-xs" style="color:var(--text-secondary)">Gemini AI sedang menganalisis rekaman Anda...</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Actions --}}
                    <div class="flex items-center gap-2 px-5 py-3" style="border-top:1px solid var(--divider);background:var(--surface-bg)">
                        {{-- View Detail --}}
                        <a href="{{ route('audio.show', $audio->id) }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-medium py-2 px-3 rounded-lg transition" style="color:var(--text-secondary)">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>

                        {{-- Edit --}}
                        @can('edit_notulensi')
                        @if($hasNotulensi)
                        <a href="{{ route('audio.edit', $audio->id) }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-medium py-2 px-3 rounded-lg transition" style="color:var(--text-secondary)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        @endif
                        @endcan

                        {{-- PDF --}}
                        @if($hasNotulensi)
                        <a href="{{ route('audio.pdf', $audio->id) }}"
                           target="_blank"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-medium py-2 px-3 rounded-lg transition" style="color:var(--text-secondary)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            PDF
                        </a>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('audio.destroy', $audio->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat ini?');" class="flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center p-2 rounded-lg transition" style="color:var(--text-muted)" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection