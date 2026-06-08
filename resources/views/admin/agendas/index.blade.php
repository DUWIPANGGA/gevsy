@extends('admin.layouts.app')
@section('title', 'Agenda')
@push('head')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<style>
    .fc { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    .fc-theme-standard th { border-color: var(--divider); padding:8px 0; font-weight:500; color: var(--text-secondary); }
    .fc-theme-standard td, .fc-theme-standard th { border-color: var(--divider); }
    .fc .fc-button-primary { background: var(--surface-bg); border-color: var(--card-border); color: var(--text-secondary); text-transform:capitalize; }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:hover { background: var(--nav-link-hover); border-color: var(--card-border); color: var(--text-primary); }
    .fc .fc-daygrid-day.fc-day-today { background:rgba(124,58,237,0.08) !important; }
    .fc .fc-daygrid-day-number { color: var(--text-primary); }
    .fc .fc-col-header-cell-cushion { color: var(--text-secondary); }
    .fc-event { cursor:pointer; border-radius:4px; padding:3px 6px; font-size:0.85em; border:none; }
    .fc-toolbar-title { font-size:1.25rem !important; font-weight:500 !important; color: var(--text-primary); }
    .fc .fc-day-other .fc-daygrid-day-number { color: var(--text-muted); }
    .fc .fc-scrollgrid { border-color: var(--divider); }
    .fc .fc-popover { background: var(--card-bg); border-color: var(--card-border); }
    .fc .fc-popover-title { color: var(--text-primary); }
    .fc .fc-popover-header { background: var(--surface-bg); }
</style>
@endpush

@section('content')
<div x-data="adminAgendaCalendar()" class="flex flex-col gap-6">
    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1>Agenda</h1>
            <p>Kalender jadwal rapat dan kegiatan</p>
        </div>
        <button @click="showCreateModal = true" type="button" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Buat Agenda
        </button>
    </div>

    <div class="card" style="height:600px;overflow:hidden">
        <div id="admin-agenda-calendar" class="h-full"></div>
    </div>

    <!-- ===================== Event Detail Modal ===================== -->
    <div x-show="showEventModal" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showEventModal = false" x-show="showEventModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="card w-full max-w-sm overflow-hidden">
            <div class="px-6 py-5 flex items-center justify-between border-b" style="border-color:var(--divider)">
                <h3 class="text-lg font-medium" style="color:var(--text-primary)">Detail</h3>
                <button @click="showEventModal = false" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1 py-0.5 px-2 rounded-full text-xs font-medium border"
                          :style="`background:${selectedEvent.badgeBg};border-color:${selectedEvent.badgeBorder};color:${selectedEvent.badgeText}`"
                          x-text="selectedEvent.tipeLabel">
                    </span>
                </div>
                <h4 class="text-xl font-medium mb-1" style="color:var(--text-primary)" x-text="selectedEvent.title"></h4>
                <p class="text-sm mb-4" style="color:var(--text-muted)" x-text="selectedEvent.time"></p>
                <div class="bg-slate-50 rounded-lg p-3 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm" style="color:var(--text-muted)">Status</span>
                        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium border"
                              :style="selectedEvent.status === 'Berlangsung' ? 'background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.3);color:#10b981' : 'background:rgba(99,102,241,0.1);border-color:rgba(99,102,241,0.3);color:#6366f1'"
                              x-text="selectedEvent.status">
                        </span>
                    </div>
                </div>
                <template x-if="selectedEvent.tipe === 'online'">
                    <a :href="selectedEvent.url" target="_blank" class="w-full flex items-center justify-center gap-2 font-medium py-2.5 px-4 rounded-lg text-white" style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Gabung Rapat
                    </a>
                </template>
                <template x-if="selectedEvent.tipe === 'offline'">
                    <div class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-slate-50 text-sm" style="color:#94a3b8">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Kegiatan Offline
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ===================== Create Agenda Modal ===================== -->
    <div x-show="showCreateModal" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showCreateModal = false" x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="card w-full max-w-md overflow-hidden">
            <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:var(--divider)">
                <div>
                    <h3 class="text-lg font-semibold" style="color:var(--text-primary)">Buat Agenda Baru</h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">Isi detail agenda yang akan dibuat</p>
                </div>
                <button @click="showCreateModal = false" class="p-1.5 hover:bg-white/10 rounded-full transition" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('meeting.create') }}" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="waktu_rapat" value="scheduled">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Agenda <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_rapat" required placeholder="Contoh: Rapat Koordinasi Bulanan"
                           class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Jenis Agenda</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_rapat" value="online" x-model="createJenis" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="flex justify-center mb-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Online</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Rapat dengan link</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_rapat" value="offline" x-model="createJenis" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="flex justify-center mb-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Offline</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Acara / Kegiatan</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Waktu <span class="text-red-400">*</span></label>
                        <input type="time" name="waktu" required
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    </div>
                </div>
                <div x-show="createJenis === 'online'" style="display: none;">
                    <div class="surface-card rounded-xl p-3 flex items-start gap-3 text-xs" style="border:1px solid rgba(139,92,246,0.2)">
                        <svg class="w-4 h-4 mt-0.5 text-violet-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="color:var(--text-muted)">Link meeting akan otomatis tergenerate untuk rapat online.</span>
                    </div>
                </div>
                <div x-show="createJenis === 'offline'" style="display: none;">
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Keterangan (opsional)</label>
                        <textarea name="keterangan" rows="3" placeholder="Lokasi, agenda, dll."
                                  class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showCreateModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl font-medium transition text-sm" style="border:1px solid var(--card-border);color:var(--text-secondary);background:var(--surface-bg)">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold transition text-sm shadow-lg shadow-violet-500/20" style="background:linear-gradient(135deg, #7c3aed, #4f46e5)">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adminAgendaCalendar', () => ({
        showEventModal: false,
        showCreateModal: false,
        createJenis: 'online',
        selectedEvent: {
            title:'', time:'', status:'', url:'#', tipe:'online', tipeLabel:'Rapat Online',
            badgeBg:'rgba(139,92,246,0.1)', badgeBorder:'rgba(139,92,246,0.3)', badgeText:'#7c3aed'
        },
        init() {
            var el = document.getElementById('admin-agenda-calendar');
            if (!el || typeof FullCalendar === 'undefined') return;
            var events = [
                @foreach($meetings as $m)
                { id:'m-{{ $m->id }}', title:@json($m->nama_rapat), start:'{{ \Carbon\Carbon::parse($m->tanggal)->format("Y-m-d") }}T{{ $m->waktu ?? "00:00" }}', extendedProps:{ status:'{{ $m->status_rapat }}', tipe:'{{ $m->tipe_rapat === "Offline" ? "offline" : "online" }}', url:'{{ $m->tipe_rapat === "Offline" ? "#" : route("meeting.room", $m->id) }}', displayTime:'{{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat("d M Y") }} - {{ $m->waktu ?? "Sepanjang Hari" }}' }, backgroundColor:'{{ $m->tipe_rapat === "Offline" ? "#F59E0B" : ($m->status_rapat === "Berlangsung" ? "#10B981" : "#7c3aed") }}' },
                @endforeach
            ];
            var cal = new FullCalendar.Calendar(el, {
                initialView:'dayGridMonth',
                headerToolbar:{ left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,timeGridDay' },
                events:events, height:600,
                eventClick:(info) => {
                    info.jsEvent.preventDefault();
                    var p = info.event.extendedProps, off = p.tipe === 'offline';
                    this.selectedEvent.title = info.event.title;
                    this.selectedEvent.time = p.displayTime;
                    this.selectedEvent.status = p.status;
                    this.selectedEvent.url = p.url;
                    this.selectedEvent.tipe = p.tipe;
                    this.selectedEvent.tipeLabel = off ? 'Kegiatan' : 'Rapat Online';
                    this.selectedEvent.badgeBg = off ? 'rgba(245,158,11,0.1)' : 'rgba(139,92,246,0.1)';
                    this.selectedEvent.badgeBorder = off ? 'rgba(245,158,11,0.3)' : 'rgba(139,92,246,0.3)';
                    this.selectedEvent.badgeText = off ? '#D97706' : '#7c3aed';
                    this.showEventModal = true;
                }
            });
            cal.render();
        }
    }));
});
</script>
@endsection
