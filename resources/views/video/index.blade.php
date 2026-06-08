@extends('layouts.app')

@section('content')
<div class="p-6 w-full max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight" style="color:var(--text-primary)">Rekaman Video</h1>
            <p class="mt-1.5" style="color:var(--text-secondary)">Daftar rekaman layar rapat yang telah Anda buat.</p>
        </div>
    </div>

    {{-- Success / Error Alert --}}
    @if (session('success'))
        <div class="surface-card px-4 py-3 rounded-xl mb-6 flex items-center gap-3" style="color:#16a34a">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="surface-card px-4 py-3 rounded-xl mb-6 flex items-center gap-3" style="color:#dc2626">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Empty State --}}
    @if($videos->isEmpty())
        <div class="page-card flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4" style="background:rgba(139,92,246,0.08)">
                <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-1" style="color:var(--text-secondary)">Belum Ada Rekaman Video</h3>
            <p class="text-sm mb-6 max-w-sm" style="color:var(--text-muted)">Rekaman layar rapat akan muncul di sini setelah Anda merekamnya melalui fitur Rekam Layar di dalam ruang rapat.</p>
            <a href="{{ route('meeting.join.form') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-lg shadow-violet-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Buat Rapat Baru
            </a>
        </div>
    @else
        {{-- Table --}}
        <div class="card overflow-hidden">
            <table>
                <thead>
                    <tr>
                        <th>Meeting</th>
                        <th>Tanggal</th>
                        <th>Durasi</th>
                        <th>Ukuran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($videos as $video)
                    <tr>
                        <td class="font-medium" style="color:var(--text-primary)">
                            {{ $video->meeting?->nama_rapat ?? '-' }}
                            <div class="text-xs" style="color:var(--text-muted)">{{ $video->meeting?->tipe_rapat ?? '-' }}</div>
                        </td>
                        <td>{{ $video->tanggal_upload ? \Carbon\Carbon::parse($video->tanggal_upload)->translatedFormat('d M Y') : $video->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $video->durasi ?? '-' }}</td>
                        <td>{{ $video->file_size_bytes ? number_format($video->file_size_bytes / 1024 / 1024, 1) . ' MB' : '-' }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('video.show', $video->id) }}"
                                   class="inline-flex items-center gap-1.5 text-sm font-medium py-1.5 px-3 rounded-lg transition" style="color:#7c3aed" title="Putar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Putar
                                </a>
                                <a href="{{ route('video.download', $video->id) }}"
                                   class="inline-flex items-center gap-1.5 text-sm font-medium py-1.5 px-3 rounded-lg transition" style="color:var(--text-secondary)" title="Download" download>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                                <form action="{{ route('video.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekaman video ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center p-1.5 rounded-lg transition" style="color:var(--text-muted)" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
                {{ $videos->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
