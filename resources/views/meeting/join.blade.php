@extends('layouts.app')

@section('content')
<div x-data="{ showNewMeetingModal: false, meetingType: 'instant' }"
     class="flex-1 flex flex-col md:flex-row items-center justify-center md:justify-between gap-10 px-6 md:px-14 py-10 w-full h-full">

    <!-- ======================== -->
    <!-- KIRI: Teks & Aksi        -->
    <!-- ======================== -->
    <div class="max-w-xl flex flex-col items-center md:items-start text-center md:text-left">

        <h1 class="text-4xl md:text-5xl leading-tight font-semibold mb-4 tracking-tight"
            style="color:var(--text-primary)">
            Rapat Video untuk<br>Semua Kebutuhan
        </h1>
        <p class="text-lg mb-10 font-light max-w-md leading-relaxed" style="color:var(--text-secondary)">
            Mulai atau gabung ke rapat dari mana saja, kapan saja — gratis dan mudah digunakan.
        </p>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full">
            <!-- Rapat Baru -->
            <button @click="showNewMeetingModal = true" type="button"
                    class="shrink-0 flex items-center justify-center gap-2 font-semibold py-3 px-5 rounded-xl transition shadow-lg shadow-violet-500/20 w-full sm:w-auto h-[48px]" style="background:linear-gradient(135deg, #7c3aed, #4f46e5);color:#fff">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Rapat Baru
            </button>

            <!-- Form Gabung -->
            <form method="POST" action="{{ route('meeting.join.submit') }}"
                  class="flex items-center relative w-full sm:w-auto surface-card p-1">
                @csrf
                <div class="absolute left-4 pointer-events-none" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <input type="number" name="meeting_id"
                       placeholder="Masukkan kode rapat"
                       required
                       class="w-full sm:w-[200px] h-[42px] pl-10 pr-3 input-theme rounded-lg font-medium text-sm outline-none transition">
                <button type="submit"
                        class="ml-1 px-4 py-2 rounded-lg font-semibold text-sm transition h-[42px] hover:bg-white/10" style="color:var(--text-primary)">
                    Gabung
                </button>
            </form>
        </div>

        @error('meeting_id')
            <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
        @enderror

        <div class="mt-8 pt-6 border-t w-full max-w-md hidden md:block text-sm" style="border-color:var(--divider);color:var(--text-muted)">
            Butuh bantuan? Hubungi administrator sistem Anda.
        </div>
    </div>

    <!-- ======================== -->
    <!-- KANAN: Ilustrasi         -->
    <!-- ======================== -->
    <div class="hidden md:flex flex-col items-center justify-center flex-shrink-0">
        <!-- Lingkaran Ilustrasi -->
        <div class="w-80 h-80 rounded-full flex items-center justify-center relative overflow-hidden mb-6 surface-card">
            <div class="absolute w-24 h-24 bg-violet-400/20 rounded-full top-8 left-10"></div>
            <div class="absolute w-36 h-36 bg-indigo-400/15 rounded-full bottom-8 right-8"></div>
            <div class="w-52 h-36 rounded-2xl z-10 flex flex-col items-center justify-center gap-4 px-5 page-card">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-600 to-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-violet-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex gap-1.5">
                    <div class="w-7 h-7 rounded-full bg-violet-300"></div>
                    <div class="w-7 h-7 rounded-full bg-green-300"></div>
                    <div class="w-7 h-7 rounded-full bg-pink-300"></div>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2 text-center" style="color:var(--text-primary)">Dapatkan tautan rapat</h2>
        <p class="text-center text-sm px-4 max-w-xs leading-relaxed" style="color:var(--text-secondary)">
            Klik <span class="font-semibold" style="color:var(--text-primary)">Rapat Baru</span> untuk membuat tautan yang bisa Anda bagikan kepada peserta rapat.
        </p>

        <div class="flex items-center gap-2 mt-5">
            <div class="w-2 h-2 rounded-full bg-violet-500"></div>
            <div class="w-2 h-2 rounded-full" style="background:var(--text-muted)"></div>
            <div class="w-2 h-2 rounded-full" style="background:var(--text-muted)"></div>
        </div>
    </div>

    <!-- ======================== -->
    <!-- MODAL: RAPAT BARU        -->
    <!-- ======================== -->
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
             class="page-card w-full max-w-md overflow-hidden">

            <!-- Header Modal -->
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

                <!-- Nama Rapat -->
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Rapat <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_rapat" required
                           placeholder="Contoh: Diskusi Tim Harian"
                           class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                </div>

                <!-- Tipe Rapat -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Tipe Rapat</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_rapat" value="instant" x-model="meetingType" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="text-lg mb-0.5">⚡</div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Instan</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Mulai sekarang</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_rapat" value="scheduled" x-model="meetingType" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="text-lg mb-0.5">📅</div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Terjadwal</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Atur jadwal</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tanggal & Waktu (jika terjadwal) -->
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

                <!-- Tombol Aksi Modal -->
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
