@extends('admin.layouts.app')
@section('title', 'Detail Meeting')

@section('content')
<div class="max-w-3xl">
    <div class="page-header">
        <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>{{ $meeting->nama_rapat }}</h1>
        <p>Detail lengkap meeting</p>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-8" style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $meeting->nama_rapat }}</h2>
                    <p class="text-violet-200 text-sm mt-1">
                        {{ \Carbon\Carbon::parse($meeting->tanggal)->translatedFormat('d M Y') }}
                        {{ $meeting->waktu ? '- '.substr($meeting->waktu, 0, 5) : '' }}
                    </p>
                </div>
                <span class="badge text-xs" style="background:{{ $meeting->status_rapat === 'Berlangsung' ? 'rgba(16,185,129,0.2)' : 'rgba(255,255,255,0.15)' }};color:{{ $meeting->status_rapat === 'Berlangsung' ? '#6ee7b7' : '#e9d5ff' }}">{{ $meeting->status_rapat }}</span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Tipe</p>
                    <span class="badge" style="background:{{ $meeting->tipe_rapat === 'Online' ? 'rgba(124,58,237,0.1)' : 'rgba(245,158,11,0.1)' }};color:{{ $meeting->tipe_rapat === 'Online' ? '#7c3aed' : '#d97706' }}">{{ $meeting->tipe_rapat }}</span>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Pembuat</p>
                    <p style="color:var(--text-primary)">{{ $meeting->creator?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Peserta</p>
                    <p style="color:var(--text-primary)">{{ $meeting->participants->count() }} peserta</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Status Pipeline</p>
                    <p style="color:var(--text-primary)">{{ $meeting->pipeline_status ?? 'Idle' }}</p>
                </div>
            </div>

            @if($meeting->deskripsi_rapat)
            <hr class="my-6" style="border-color:var(--divider)">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color:var(--text-muted)">Deskripsi</p>
                <p style="color:var(--text-secondary);line-height:1.7">{{ $meeting->deskripsi_rapat }}</p>
            </div>
            @endif

            @if($meeting->link_meeting)
            <hr class="my-6" style="border-color:var(--divider)">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color:var(--text-muted)">Link Meeting</p>
                <a href="{{ $meeting->link_meeting }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg transition" style="background:rgba(99,102,241,0.1);color:#6366f1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    {{ $meeting->link_meeting }}
                </a>
            </div>
            @endif

            @if($meeting->rekamanAudio->count())
            <hr class="my-6" style="border-color:var(--divider)">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color:var(--text-muted)">Rekaman Audio ({{ $meeting->rekamanAudio->count() }})</p>
                <div class="space-y-2">
                    @foreach($meeting->rekamanAudio as $rek)
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background:var(--surface-bg);border:1px solid var(--card-border)">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(16,185,129,0.1)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#10b981"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            </div>
                            <span class="text-sm" style="color:var(--text-secondary)">{{ $rek->created_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <span class="text-xs" style="color:var(--text-muted)">{{ number_format($rek->durasi ?? 0, 0) }}s</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($meeting->notulensi)
            <hr class="my-6" style="border-color:var(--divider)">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color:var(--text-muted)">Notulensi</p>
                <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background:rgba(124,58,237,0.05);border:1px solid rgba(124,58,237,0.15)">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(124,58,237,0.1)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-medium" style="color:var(--text-primary)">Notulensi</span>
                            <p class="text-xs" style="color:var(--text-muted)">Dibuat: {{ $meeting->notulensi->created_at->translatedFormat('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($meeting->notulensi->file_pdf)
                    <a href="{{ asset('storage/'.$meeting->notulensi->file_pdf) }}" target="_blank" class="btn-secondary text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if($meeting->agendas->count())
            <hr class="my-6" style="border-color:var(--divider)">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color:var(--text-muted)">Agenda Terkait</p>
                <div class="space-y-2">
                    @foreach($meeting->agendas as $agenda)
                    <div class="px-4 py-3 rounded-xl" style="background:var(--surface-bg);border:1px solid var(--card-border)">
                        <p class="text-sm font-medium" style="color:var(--text-primary)">{{ $agenda->nama_agenda ?? $agenda->judul }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-3 mt-8 pt-6 border-t" style="border-color:var(--divider)">
                <a href="{{ route('admin.meetings.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection