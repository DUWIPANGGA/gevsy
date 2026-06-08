@extends('layouts.app')

@section('content')
<style>
    /* Word-like document feel */
    .doc-section {
        border-left: 3px solid transparent;
        transition: border-color 0.2s ease;
    }
    .doc-section:focus-within {
        border-left-color: #7c3aed;
    }
    .doc-input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.9375rem;
        color: var(--text-primary);
        line-height: 1.7;
        resize: none;
        min-height: 2.5rem;
    }
    .doc-input::placeholder { color: var(--text-muted); }
    .doc-input:focus { background: var(--surface-bg); border-radius: 8px; }

    .row-input {
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.875rem;
        width: 100%;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: var(--input-bg);
        color: var(--text-primary);
    }
    .row-input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
    }
    .row-input::placeholder { color: var(--text-muted); }

    .add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #7c3aed;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px dashed rgba(139,92,246,0.3);
        background: rgba(139,92,246,0.06);
        transition: all 0.15s ease;
        width: 100%;
        justify-content: center;
        margin-top: 8px;
    }
    .add-btn:hover { background: rgba(139,92,246,0.1); border-color: #7c3aed; }

    .remove-btn {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.15s;
        border: none;
        background: none;
    }
    .remove-btn:hover { color: #ef4444; background: rgba(239,68,68,0.1); }

    /* Drag handle style */
    .item-row { transition: background 0.15s; }
    .item-row:hover { background: var(--surface-bg); border-radius: 8px; }
</style>

<div class="p-6 w-full" x-data="notulensiEditor()" x-init="init()">

    {{-- Back Button --}}
    <a href="{{ route('audio.show', $liveAudio->id) }}"
       class="inline-flex items-center gap-2 text-sm mb-6 transition group" style="color:var(--text-secondary)">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Detail
    </a>

    {{-- Document Header --}}
    <div class="page-card overflow-hidden mb-6">

        {{-- Doc Toolbar --}}
        <div class="flex items-center justify-between px-6 py-3" style="border-bottom:1px solid var(--divider);background:var(--surface-bg)">
            <div class="flex items-center gap-3">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full" style="background:#ef4444"></div>
                    <div class="w-3 h-3 rounded-full" style="background:#eab308"></div>
                    <div class="w-3 h-3 rounded-full" style="background:#22c55e"></div>
                </div>
            </div>
        </div>

        {{-- Document Title Area --}}
        <div class="px-10 py-8" style="border-bottom:1px solid var(--divider);background:var(--card-bg)">
            <h1 class="text-2xl font-bold mb-1" style="color:var(--text-primary)">
                Notulensi Rapat
            </h1>
            <p style="color:var(--text-muted)" class="text-sm">
                Rekaman {{ $liveAudio->tanggal_rekam?->format('d F Y, H:i') ?? $liveAudio->created_at->format('d F Y, H:i') }} WIB
                &nbsp;·&nbsp; {{ $liveAudio->user->name ?? auth()->user()->name }}
            </p>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
        <div class="mx-10 mt-6 surface-card px-4 py-3 rounded-xl flex items-start gap-3" style="border-color:rgba(239,68,68,0.3)">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                @foreach($errors->all() as $err)<p class="text-sm" style="color:var(--text-secondary)">{{ $err }}</p>@endforeach
            </div>
        </div>
        @endif

        {{-- ============================ --}}
        {{-- DOCUMENT BODY               --}}
        {{-- ============================ --}}
        <div class="px-10 py-8 space-y-10">

            {{-- 1. RINGKASAN EKSEKUTIF --}}
            <div class="doc-section pl-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(139,92,246,0.1)">
                        <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-violet-600">Ringkasan Eksekutif</h2>
                </div>
                <textarea
                    x-model="form.ringkasan"
                    x-ref="ringkasan"
                    @input="autoResize($refs.ringkasan)"
                    class="doc-input rounded-lg px-3 py-2" style="background:var(--surface-bg)"
                    placeholder="Tulis ringkasan singkat jalannya rapat di sini..."
                    rows="4"
                ></textarea>
            </div>

            <hr style="border-color:var(--divider)">

            {{-- 2. TOPIK DIBAHAS --}}
            <div class="doc-section pl-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(139,92,246,0.1)">
                        <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-violet-600">Topik Dibahas</h2>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="color:var(--text-muted);background:var(--surface-bg)" x-text="form.topik_dibahas.length + ' topik'"></span>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, index) in form.topik_dibahas" :key="'topik-'+index">
                        <div class="item-row flex items-center gap-2 p-1">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center" style="background:rgba(139,92,246,0.1);color:#7c3aed" x-text="index + 1"></span>
                            <input type="text"
                                   x-model="form.topik_dibahas[index]"
                                   class="row-input"
                                   placeholder="Tulis topik yang dibahas..."
                                   @keydown.enter.prevent="addItem('topik_dibahas', index)">
                            <button type="button" @click="removeItem('topik_dibahas', index)" class="remove-btn" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="form.topik_dibahas.push('')" class="add-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Topik
                </button>
            </div>

            <hr style="border-color:var(--divider)">

            {{-- 3. KEPUTUSAN RAPAT --}}
            <div class="doc-section pl-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(34,197,94,0.1)">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-green-600">Keputusan Rapat</h2>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="color:var(--text-muted);background:var(--surface-bg)" x-text="form.keputusan.length + ' keputusan'"></span>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, index) in form.keputusan" :key="'keputusan-'+index">
                        <div class="item-row flex items-center gap-2 p-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <input type="text"
                                   x-model="form.keputusan[index]"
                                   class="row-input"
                                   placeholder="Tulis keputusan yang diambil..."
                                   @keydown.enter.prevent="addItem('keputusan', index)">
                            <button type="button" @click="removeItem('keputusan', index)" class="remove-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="form.keputusan.push('')" class="add-btn" style="color:#16a34a;border-color:rgba(34,197,94,0.3);background:rgba(34,197,94,0.06)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Keputusan
                </button>
            </div>

            <hr style="border-color:var(--divider)">

            {{-- 4. ACTION ITEMS --}}
            <div class="doc-section pl-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(249,115,22,0.1)">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-orange-600">Action Items</h2>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="color:var(--text-muted);background:var(--surface-bg)" x-text="form.action_items.length + ' item'"></span>
                </div>

                {{-- Table Header --}}
                <div class="grid grid-cols-12 gap-2 px-1 mb-2">
                    <div class="col-span-5 text-xs font-semibold uppercase tracking-wide" style="color:var(--text-muted)">Tugas</div>
                    <div class="col-span-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--text-muted)">PIC</div>
                    <div class="col-span-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--text-muted)">Deadline</div>
                    <div class="col-span-1"></div>
                </div>

                <div class="space-y-2">
                    <template x-for="(item, index) in form.action_items" :key="'action-'+index">
                        <div class="item-row grid grid-cols-12 gap-2 p-1 items-center">
                            <div class="col-span-5">
                                <input type="text"
                                       x-model="form.action_items[index].task"
                                       class="row-input"
                                       placeholder="Deskripsi tugas...">
                            </div>
                            <div class="col-span-3">
                                <div class="relative">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <input type="text"
                                           x-model="form.action_items[index].pic"
                                           class="row-input pl-7"
                                           placeholder="Nama PIC">
                                </div>
                            </div>
                            <div class="col-span-3">
                                <div class="relative">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <input type="text"
                                           x-model="form.action_items[index].deadline"
                                           class="row-input pl-7"
                                           placeholder="Deadline">
                                </div>
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <button type="button" @click="removeItem('action_items', index)" class="remove-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button"
                        @click="form.action_items.push({task: '', pic: '', deadline: ''})"
                        class="add-btn"
                        style="color:#ea580c;border-color:rgba(249,115,22,0.3);background:rgba(249,115,22,0.06)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Action Item
                </button>
            </div>

            <hr style="border-color:var(--divider)">

            {{-- 5. RISIKO & CATATAN --}}
            <div class="doc-section pl-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(239,68,68,0.1)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h2 class="text-xs font-bold uppercase tracking-widest" style="color:var(--text-secondary)">Risiko & Catatan</h2>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="color:var(--text-muted);background:var(--surface-bg)" x-text="form.risiko_catatan.length + ' catatan'"></span>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, index) in form.risiko_catatan" :key="'risiko-'+index">
                        <div class="item-row flex items-center gap-2 p-1">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <input type="text"
                                   x-model="form.risiko_catatan[index]"
                                   class="row-input"
                                   placeholder="Tulis risiko atau catatan penting..."
                                   @keydown.enter.prevent="addItem('risiko_catatan', index)">
                            <button type="button" @click="removeItem('risiko_catatan', index)" class="remove-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="form.risiko_catatan.push('')" class="add-btn" style="color:#dc2626;border-color:rgba(239,68,68,0.3);background:rgba(239,68,68,0.06)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Catatan
                </button>
            </div>

        </div>{{-- end document body --}}

    </div>{{-- end document card --}}

    {{-- Action Bar --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('audio.show', $liveAudio->id) }}"
           class="inline-flex items-center gap-2 font-medium py-2.5 px-5 rounded-xl transition surface-card text-sm" style="color:var(--text-secondary);border:1px solid var(--card-border)">
            Batal
        </a>
        <button type="button" @click="submitForm()"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-semibold py-2.5 px-6 rounded-xl transition shadow-lg shadow-violet-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Simpan Notulensi
        </button>
    </div>

    {{-- Hidden form for submission --}}
    <form id="saveForm" action="{{ route('audio.update', $liveAudio->id) }}" method="POST" style="display:none;">
        @csrf
        @method('PUT')
        <input type="hidden" name="notulensi_teks" id="notulensiJsonInput">
    </form>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notulensiEditor', () => ({
        form: {
            ringkasan: '',
            topik_dibahas: [],
            keputusan: [],
            action_items: [],
            risiko_catatan: [],
        },

        init() {
            // Parse existing notulensi JSON and populate form
            const raw = @json($liveAudio->notulensi_teks ?? 'null');
            if (raw) {
                try {
                    const parsed = JSON.parse(raw);
                    this.form.ringkasan     = parsed.ringkasan      ?? '';
                    this.form.topik_dibahas = Array.isArray(parsed.topik_dibahas) ? parsed.topik_dibahas : (parsed.topik_dibahas ? [parsed.topik_dibahas] : ['']);
                    this.form.keputusan     = Array.isArray(parsed.keputusan)     ? parsed.keputusan     : (parsed.keputusan ? [parsed.keputusan] : ['']);
                    this.form.action_items  = Array.isArray(parsed.action_items)  ? parsed.action_items.map(i => typeof i === 'object' ? i : {task: i, pic: '-', deadline: '-'}) : [{task:'', pic:'', deadline:''}];
                    this.form.risiko_catatan= Array.isArray(parsed.risiko_catatan)? parsed.risiko_catatan: (parsed.risiko_catatan ? [parsed.risiko_catatan] : ['']);
                } catch(e) {
                    // Fallback: empty form
                    this.form.topik_dibahas  = [''];
                    this.form.keputusan      = [''];
                    this.form.action_items   = [{task: '', pic: '', deadline: ''}];
                    this.form.risiko_catatan = [''];
                }
            } else {
                this.form.topik_dibahas  = [''];
                this.form.keputusan      = [''];
                this.form.action_items   = [{task: '', pic: '', deadline: ''}];
                this.form.risiko_catatan = [''];
            }

            // Auto-resize ringkasan textarea
            this.$nextTick(() => {
                if (this.$refs.ringkasan) this.autoResize(this.$refs.ringkasan);
            });
        },

        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        },

        addItem(field, afterIndex) {
            if (field === 'action_items') {
                this.form[field].splice(afterIndex + 1, 0, {task: '', pic: '', deadline: ''});
            } else {
                this.form[field].splice(afterIndex + 1, 0, '');
            }
        },

        removeItem(field, index) {
            if (this.form[field].length <= 1) {
                // Reset instead of remove if only one item
                if (field === 'action_items') {
                    this.form[field][index] = {task: '', pic: '', deadline: ''};
                } else {
                    this.form[field][index] = '';
                }
                return;
            }
            this.form[field].splice(index, 1);
        },

        submitForm() {
            // Clean up empty items before saving
            const cleaned = {
                ringkasan:      this.form.ringkasan.trim(),
                topik_dibahas:  this.form.topik_dibahas.filter(i => i.trim() !== ''),
                keputusan:      this.form.keputusan.filter(i => i.trim() !== ''),
                action_items:   this.form.action_items.filter(i => {
                    if (typeof i === 'object') return (i.task ?? '').trim() !== '';
                    return String(i).trim() !== '';
                }).map(i => ({
                    task:     (i.task ?? '').trim()     || '-',
                    pic:      (i.pic ?? '').trim()      || '-',
                    deadline: (i.deadline ?? '').trim() || '-',
                })),
                risiko_catatan: this.form.risiko_catatan.filter(i => i.trim() !== ''),
            };

            // Validate ringkasan is not empty
            if (!cleaned.ringkasan) {
                alert('Ringkasan eksekutif tidak boleh kosong.');
                return;
            }

            // Set hidden input and submit
            document.getElementById('notulensiJsonInput').value = JSON.stringify(cleaned);
            document.getElementById('saveForm').submit();
        },
    }));
});
</script>
@endsection