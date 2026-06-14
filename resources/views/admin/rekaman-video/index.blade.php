@extends('admin.layouts.app')
@section('title', 'Rekaman Video')

@section('content')
<div>
    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1>Rekaman Video</h1>
            <p>Total {{ $rekamans->total() }} rekaman video tersimpan</p>
        </div>
    </div>

    @if($rekamans->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($rekamans as $rek)
        <div class="card overflow-hidden flex flex-col">
            <div class="relative bg-black" style="aspect-ratio:16/9">
                <video
                    preload="metadata"
                    style="width:100%;height:100%;object-fit:contain"
                    src="{{ route('admin.rekaman-video.stream', $rek->id) }}#t=0.1">
                </video>
                <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 hover:opacity-100 transition-opacity">
                    <a href="{{ route('admin.rekaman-video.stream', $rek->id) }}" target="_blank"
                       class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-transform hover:scale-110"
                       style="background:rgba(124,58,237,0.9);color:#fff">
                        <svg class="w-6 h-6 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge" style="background:rgba(124,58,237,0.1);color:#7c3aed">Video</span>
                        @if($rek->durasi)
                        <span class="text-xs" style="color:var(--text-muted)">{{ $rek->durasi }}</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-sm truncate" style="color:var(--text-primary)">
                        {{ $rek->meeting?->nama_rapat ?? 'Tanpa Meeting' }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">
                        {{ $rek->tanggal_upload ? \Carbon\Carbon::parse($rek->tanggal_upload)->translatedFormat('d M Y') : $rek->created_at->translatedFormat('d M Y') }}
                        @if($rek->file_size_bytes)
                        &middot; {{ number_format($rek->file_size_bytes / 1024 / 1024, 1) }} MB
                        @endif
                    </p>
                </div>
                <div class="flex items-center justify-between pt-2" style="border-top:1px solid var(--divider)">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.rekaman-video.stream', $rek->id) }}" target="_blank"
                           class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Putar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                        <a href="{{ route('admin.rekaman-video.download', $rek->id) }}"
                           class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-secondary)"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                    </div>
                    <form action="{{ route('admin.rekaman-video.destroy', $rek->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekaman video ini?');" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-5">
        {{ $rekamans->links() }}
    </div>
    @else
    <div class="card">
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:40px;height:40px;margin:0 auto 12px;display:block;color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <p>Belum ada rekaman video.</p>
        </div>
    </div>
    @endif
</div>
<style>
    .card video::-webkit-media-controls { display:none !important; }
</style>
@endsection