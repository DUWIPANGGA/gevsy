<div>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <div class="p-6" x-data="adminAgendaCalendar()">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Agenda</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jadwal rapat dan kegiatan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('admin/agendas/create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Buat Agenda Baru
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-5 min-h-[600px]">
            <div id="admin-calendar" class="h-[600px]"></div>
        </div>

        <div x-show="showEventModal"
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
            <div @click.away="showEventModal = false"
                 x-show="showEventModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-sm overflow-hidden border border-gray-200 dark:border-gray-700">

                <div class="px-6 py-5 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail</h3>
                    <button @click="showEventModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
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
                    <h4 class="text-xl font-medium mb-1 text-gray-900 dark:text-white" x-text="selectedEvent.title"></h4>
                    <p class="text-sm mb-4 text-gray-500 dark:text-gray-400" x-text="selectedEvent.time"></p>

                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium border"
                                  x-text="selectedEvent.status">
                            </span>
                        </div>
                    </div>

                    <template x-if="selectedEvent.tipe === 'online'">
                        <a :href="selectedEvent.url" target="_blank"
                           class="w-full flex items-center justify-center gap-2 font-medium py-2.5 px-4 rounded-lg transition shadow-lg text-white"
                           style="background:linear-gradient(135deg, #7c3aed, #4f46e5)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Gabung Rapat
                        </a>
                    </template>
                    <template x-if="selectedEvent.tipe === 'offline'">
                        <div class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Kegiatan Offline
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fc { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .fc-theme-standard th {
            border-color: #e5e7eb;
            padding: 8px 0;
            font-weight: 500;
            color: #6b7280;
        }
        .dark .fc-theme-standard th { border-color: #374151; color: #9ca3af; }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #e5e7eb; }
        .dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #374151; }
        .fc .fc-button-primary {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
            text-transform: capitalize;
        }
        .dark .fc .fc-button-primary { background: #1f2937; border-color: #4b5563; color: #d1d5db; }
        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:hover { background: #e5e7eb; border-color: #9ca3af; color: #111827; }
        .dark .fc .fc-button-primary:not(:disabled):active,
        .dark .fc .fc-button-primary:not(:disabled).fc-button-active,
        .dark .fc .fc-button-primary:hover { background: #374151; border-color: #6b7280; color: #f3f4f6; }
        .fc .fc-button-primary:disabled { opacity: 0.5; }
        .fc .fc-daygrid-day.fc-day-today { background: rgba(139,92,246,0.08) !important; }
        .fc .fc-daygrid-day-number { color: #111827; }
        .dark .fc .fc-daygrid-day-number { color: #f3f4f6; }
        .fc .fc-col-header-cell-cushion { color: #6b7280; }
        .dark .fc .fc-col-header-cell-cushion { color: #9ca3af; }
        .fc .fc-daygrid-more-link { color: #7c3aed; }
        .fc-event { cursor: pointer; border-radius: 4px; padding: 3px 6px; font-size: 0.85em; border: none; transition: transform 0.1s; }
        .fc-event:hover { transform: scale(1.02); }
        .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 500 !important; color: #111827; }
        .dark .fc-toolbar-title { color: #f3f4f6; }
        .fc .fc-day-other .fc-daygrid-day-number { color: #9ca3af; }
        .fc .fc-non-business { background: transparent; }
        .fc .fc-scrollgrid { border-color: #e5e7eb; }
        .dark .fc .fc-scrollgrid { border-color: #374151; }
        .fc .fc-popover { background: #fff; border-color: #d1d5db; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .dark .fc .fc-popover { background: #1f2937; border-color: #4b5563; }
        .fc .fc-popover-title { color: #111827; }
        .dark .fc .fc-popover-title { color: #f3f4f6; }
        .fc .fc-popover-header { background: #f9fafb; }
        .dark .fc .fc-popover-header { background: #111827; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Alpine !== 'undefined') {
                Alpine.data('adminAgendaCalendar', () => ({
                    showEventModal: false,
                    selectedEvent: {
                        title: '', time: '', status: '', url: '#',
                        tipe: 'online', tipeLabel: 'Rapat Online',
                        badgeBg: 'rgba(139,92,246,0.1)', badgeBorder: 'rgba(139,92,246,0.3)', badgeText: '#7c3aed'
                    },
                    init() {
                        var calendarEl = document.getElementById('admin-calendar');
                        if (!calendarEl) return;

                        var events = [
                            @foreach($this->getMeetings() as $meeting)
                            {
                                id: 'm-{{ $meeting->id }}',
                                title: '{!! addslashes($meeting->nama_rapat) !!}',
                                start: '{{ \Carbon\Carbon::parse($meeting->tanggal)->format("Y-m-d") }}T{{ $meeting->waktu ?? "00:00:00" }}',
                                extendedProps: {
                                    status: '{{ $meeting->status_rapat }}',
                                    tipe: '{{ $meeting->tipe_rapat === "Offline" ? "offline" : "online" }}',
                                    url: '{{ $meeting->tipe_rapat === "Offline" ? "#" : route("meeting.room", $meeting->id) }}',
                                    displayTime: '{{ \Carbon\Carbon::parse($meeting->tanggal)->translatedFormat("d M Y") }} - {{ $meeting->waktu ?? "Sepanjang Hari" }}'
                                },
                                backgroundColor: '{{ $meeting->tipe_rapat === "Offline" ? "#F59E0B" : ($meeting->status_rapat === "Berlangsung" ? "#10B981" : "#7c3aed") }}'
                            },
                            @endforeach
                        ];

                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            events: events,
                            height: '100%',
                            eventClick: (info) => {
                                info.jsEvent.preventDefault();
                                var props = info.event.extendedProps;
                                var isOffline = props.tipe === 'offline';
                                this.selectedEvent.title = info.event.title;
                                this.selectedEvent.time = props.displayTime;
                                this.selectedEvent.status = props.status;
                                this.selectedEvent.url = props.url;
                                this.selectedEvent.tipe = props.tipe;
                                this.selectedEvent.tipeLabel = isOffline ? 'Kegiatan' : 'Rapat Online';
                                this.selectedEvent.badgeBg = isOffline ? 'rgba(245,158,11,0.1)' : 'rgba(139,92,246,0.1)';
                                this.selectedEvent.badgeBorder = isOffline ? 'rgba(245,158,11,0.3)' : 'rgba(139,92,246,0.3)';
                                this.selectedEvent.badgeText = isOffline ? '#D97706' : '#7c3aed';
                                this.showEventModal = true;
                            }
                        });

                        calendar.render();
                    }
                }));
            }
        });
    </script>
</div>