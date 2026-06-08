@extends('admin.layouts.app')
@section('title', 'Arsip')

@section('content')
<div class="page-header">
    <h1>Arsip</h1>
    <p>Dokumen arsip notulensi meeting yang telah selesai</p>
</div>

<div class="card overflow-hidden">
    @if($arsips->count())
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Meeting</th>
                    <th>Notulensi</th>
                    <th>Tanggal Arsip</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arsips as $arsip)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.1)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </div>
                            <span class="font-medium" style="color:var(--text-primary)">{{ $arsip->meeting?->nama_rapat ?? '-' }}</span>
                        </div>
                    </td>
                    <td>{{ $arsip->notulensi?->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                    <td style="color:var(--text-primary)">{{ $arsip->tanggal_arsip ? \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d M Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($arsips->hasPages())
    <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
        {{ $arsips->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        <p>Belum ada arsip.</p>
    </div>
    @endif
</div>
@endsection