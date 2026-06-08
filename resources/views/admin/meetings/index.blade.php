@extends('admin.layouts.app')
@section('title', 'Meetings')

@section('content')
<div x-data="{ showNewMeetingModal: false, meetingType: 'instant' }">
    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1>Meetings</h1>
            <p>Total {{ $meetings->total() }} meeting terselenggara</p>
        </div>
        <button @click="showNewMeetingModal = true" type="button"
                class="btn-primary shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Rapat Baru
        </button>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Pembuat</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $m)
                    <tr>
                        <td class="font-medium" style="color:var(--text-primary)">{{ $m->nama_rapat }}</td>
                        <td><span class="badge" style="background:{{ $m->tipe_rapat === 'Online' ? 'rgba(124,58,237,0.1)' : 'rgba(245,158,11,0.1)' }};color:{{ $m->tipe_rapat === 'Online' ? '#7c3aed' : '#d97706' }}">{{ $m->tipe_rapat }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }} {{ $m->waktu ? substr($m->waktu, 0, 5) : '' }}</td>
                        <td>{{ $m->creator?->name ?? '-' }}</td>
                        <td><span class="badge" style="background:{{ $m->status_rapat === 'Berlangsung' ? 'rgba(16,185,129,0.1)' : 'rgba(99,102,241,0.1)' }};color:{{ $m->status_rapat === 'Berlangsung' ? '#10b981' : '#6366f1' }}">{{ $m->status_rapat }}</span></td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.meetings.show', $m) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6366f1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <p>Belum ada meeting.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($meetings->hasPages())
        <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
            {{ $meetings->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Buat Rapat --}}
    <div x-show="showNewMeetingModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showNewMeetingModal = false"
             x-show="showNewMeetingModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="card w-full max-w-md overflow-hidden">

            <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:var(--divider)">
                <div>
                    <h3 class="text-lg font-semibold" style="color:var(--text-primary)">Buat Rapat Baru</h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">Isi detail rapat yang akan dibuat</p>
                </div>
                <button @click="showNewMeetingModal = false"
                        class="p-1.5 hover:bg-white/10 rounded-full transition" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('meeting.create') }}" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="jenis_rapat" value="online">

                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Rapat <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_rapat" required
                           placeholder="Contoh: Diskusi Tim Harian"
                           class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Tipe Rapat</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_rapat" value="instant" x-model="meetingType" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="mb-1 flex justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Instan</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Mulai sekarang</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_rapat" value="scheduled" x-model="meetingType" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="mb-1 flex justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Terjadwal</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Atur jadwal</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div x-show="meetingType === 'scheduled'" style="display: none;" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal</label>
                        <input type="date" name="tanggal"
                               :required="meetingType === 'scheduled'"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Waktu</label>
                        <input type="time" name="waktu"
                               :required="meetingType === 'scheduled'"
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    </div>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showNewMeetingModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl font-medium transition text-sm" style="border:1px solid var(--card-border);color:var(--text-secondary);background:var(--surface-bg)">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold transition text-sm shadow-lg shadow-violet-500/20" style="background:linear-gradient(135deg, #7c3aed, #4f46e5)">
                        Buat Rapat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection