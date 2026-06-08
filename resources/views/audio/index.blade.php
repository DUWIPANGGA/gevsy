@extends('layouts.app')

@section('content')
<style>
    @keyframes subtlePulse {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .recording-pulse { animation: subtlePulse 2s infinite; }

    @keyframes progressPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .progress-pulse { animation: progressPulse 1.5s ease-in-out infinite; }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        backdrop-filter: blur(12px);
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        border-color: rgba(139,92,246,0.2);
        box-shadow: 0 10px 25px -5px rgba(139,92,246,0.15);
        transform: translateY(-2px);
    }
    .canvas-container {
        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }

    /* Step indicator */
    .step-item { transition: all 0.4s ease; }
    .step-item.active .step-icon { background: #7c3aed; color: white; }
    .step-item.done .step-icon { background: #22c55e; color: white; }
    .step-item.error .step-icon { background: #ef4444; color: white; }
    .step-item.idle .step-icon { background: var(--surface-bg); color: var(--text-muted); }
    .step-connector { height: 2px; flex: 1; transition: background 0.4s ease; }
    .step-connector.done { background: #22c55e; }
    .step-connector.idle { background: var(--divider); }
</style>

<div x-data="audioRecorder()" x-init="init()" class="w-full h-full flex flex-col relative overflow-hidden" style="color:var(--text-primary);background:transparent">

    <!-- Background blobs -->
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full pointer-events-none" style="background:radial-gradient(circle, rgba(139,92,246,0.06) 0%, transparent 70%)"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full pointer-events-none" style="background:radial-gradient(circle, rgba(99,102,241,0.04) 0%, transparent 70%)"></div>

    <!-- ======================== -->
    <!-- STATE: MENU              -->
    <!-- ======================== -->
    <div x-show="state === 'menu'"
         x-transition:enter="transition ease-out duration-500 delay-100"
         x-transition:enter-start="opacity-0 translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute inset-0 flex flex-col items-center justify-center p-8 z-10">

        <div class="text-center mb-12">
            <h2 class="text-4xl font-normal tracking-tight mb-3" style="color:var(--text-primary)">
                Audio <span class="text-violet-600">Notulensi</span>
            </h2>
            <p style="color:var(--text-secondary)" class="text-lg">Rekam atau upload audio rapat — AI akan otomatis buat notulensinya.</p>
            <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium border surface-card" style="color:var(--text-secondary);border-color:rgba(139,92,246,0.2)">
                <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Whisper → Gemini AI → Tersimpan otomatis
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-stretch justify-center gap-6 w-full max-w-3xl">
            <!-- Upload Card -->
            <div @click="$refs.fileInput.click()"
                 class="glass-card flex-1 p-10 rounded-2xl cursor-pointer group flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6 transition-colors" style="background:rgba(139,92,246,0.1)">
                    <svg class="w-10 h-10 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <h3 class="text-xl font-medium mb-2" style="color:var(--text-primary)">Upload Audio</h3>
                <p style="color:var(--text-secondary)" class="text-sm">Pilih file audio dari perangkat Anda (.mp3, .wav, .webm)</p>
                <input type="file" x-ref="fileInput" @change="handleFileUpload" accept="audio/*" class="hidden">
            </div>

            <!-- Record Card -->
            <div @click="state = 'record'; $nextTick(() => resizeCanvas())"
                 class="glass-card flex-1 p-10 rounded-2xl cursor-pointer group flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6 transition-colors" style="background:rgba(239,68,68,0.08)">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-medium mb-2" style="color:var(--text-primary)">Rekam Langsung</h3>
                <p style="color:var(--text-secondary)" class="text-sm">Rekam suara dari mikrofon Anda secara real-time</p>
            </div>
        </div>
    </div>

    <!-- ======================== -->
    <!-- STATE: RECORDING         -->
    <!-- ======================== -->
    <div x-show="state === 'record'" style="display:none;"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 scale-105"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute inset-0 flex flex-col z-10">

        <!-- Top bar -->
        <div class="w-full px-8 py-5 flex items-center justify-between z-30" style="border-bottom:1px solid var(--divider);background:var(--glass-bg);backdrop-filter:blur(12px)">
            <button @click="cancelRecording()" class="flex items-center gap-2 transition group" style="color:var(--text-secondary)">
                <div class="w-8 h-8 rounded-full flex items-center justify-center transition" style="background:var(--surface-bg)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="text-sm font-medium">Kembali</span>
            </button>
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span x-show="isRecording" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3" :class="isRecording ? 'bg-red-500' : ''" style="background:var(--text-muted)"></span>
                </span>
                <span class="text-sm font-semibold tracking-wider uppercase" :class="isRecording ? 'text-red-500' : ''" style="color:var(--text-muted)"
                      x-text="isRecording ? 'RECORDING' : 'READY'"></span>
            </div>
        </div>

        <!-- Visualizer -->
        <div class="flex-1 w-full flex items-center justify-center canvas-container px-8 relative" style="background:var(--surface-bg)">
            <div class="absolute inset-y-0 left-1/2 w-px" style="background:var(--divider)"></div>
            <canvas id="visualizerCanvas" class="w-full h-48 z-10"></canvas>
        </div>

        <!-- Controls -->
        <div class="h-56 w-full flex flex-col items-center justify-center gap-5 z-30" style="border-top:1px solid var(--divider);background:var(--glass-bg);backdrop-filter:blur(12px)">
            <div x-text="timerText" class="text-5xl font-mono font-medium tracking-tighter tabular-nums" style="color:var(--text-primary)">00:00:00</div>
            <div class="flex items-center gap-6">
                <!-- Record / Stop -->
                <button @click="toggleRecording()"
                        class="w-16 h-16 rounded-full flex items-center justify-center transition-all duration-300 shadow-md"
                        :class="isRecording ? 'recording-pulse' : 'hover:-translate-y-1'"
                        :style="isRecording ? 'background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2)' : 'background:var(--card-bg);border:1px solid var(--card-border)'">
                    <div x-show="isRecording" class="w-6 h-6 bg-red-500 rounded-sm"></div>
                    <svg x-show="!isRecording" class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                </button>
                <!-- Process Button -->
                <button @click="processAudio(new Blob(audioChunks, {type: 'audio/webm'}), 'recording.webm')"
                        x-show="!isRecording && audioChunks.length > 0"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-[-20px]"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:-translate-y-1 transition-all text-white" style="background:linear-gradient(135deg, #7c3aed, #6366f1)">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
            <p x-show="!isRecording && audioChunks.length === 0" class="text-xs" style="color:var(--text-muted)">Tekan tombol mikrofon untuk mulai merekam</p>
            <p x-show="!isRecording && audioChunks.length > 0" class="text-xs" style="color:var(--text-secondary)">Rekaman selesai — tekan ✓ untuk proses dengan AI</p>
        </div>
    </div>

    <!-- ======================== -->
    <!-- STATE: PROCESSING        -->
    <!-- ======================== -->
    <div x-show="state === 'processing'" style="display:none;"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute inset-0 flex flex-col items-center justify-center p-8 z-10">

        <div class="w-full max-w-lg">
            <!-- Title -->
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold mb-2" style="color:var(--text-primary)">Memproses Audio...</h2>
                <p style="color:var(--text-secondary)" class="text-sm">Harap tunggu, jangan tutup halaman ini</p>
            </div>

            <!-- Step Indicator -->
            <div class="flex items-center mb-10 px-4">
                <!-- Step 1: Whisper -->
                <div class="flex flex-col items-center step-item" :class="getStepClass(1)">
                    <div class="step-icon w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-400" style="background:var(--surface-bg)">
                        <template x-if="step > 1"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                        <template x-if="step === 1 && !stepError"><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></template>
                        <template x-if="step === 1 && stepError"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></template>
                        <span x-show="step < 1">1</span>
                    </div>
                    <p class="text-xs mt-2 font-medium text-center" :class="step === 1 ? 'text-violet-600' : (step > 1 ? 'text-green-600' : '')" style="color:var(--text-muted)">Whisper</p>
                    <p class="text-xs" style="color:var(--text-muted)">Transkripsi</p>
                </div>

                <div class="step-connector mx-2" :class="step > 1 ? 'done' : 'idle'"></div>

                <!-- Step 2: Gemini -->
                <div class="flex flex-col items-center step-item" :class="getStepClass(2)">
                    <div class="step-icon w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-400" style="background:var(--surface-bg)">
                        <template x-if="step > 2"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                        <template x-if="step === 2 && !stepError"><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></template>
                        <template x-if="step === 2 && stepError"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></template>
                        <span x-show="step < 2">2</span>
                    </div>
                    <p class="text-xs mt-2 font-medium text-center" :class="step === 2 ? 'text-violet-600' : (step > 2 ? 'text-green-600' : '')" style="color:var(--text-muted)">Gemini AI</p>
                    <p class="text-xs" style="color:var(--text-muted)">Notulensi</p>
                </div>

                <div class="step-connector mx-2" :class="step > 2 ? 'done' : 'idle'"></div>

                <!-- Step 3: Simpan -->
                <div class="flex flex-col items-center step-item" :class="getStepClass(3)">
                    <div class="step-icon w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-400" style="background:var(--surface-bg)">
                        <template x-if="step > 3"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></template>
                        <template x-if="step === 3 && !stepError"><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></template>
                        <template x-if="step === 3 && stepError"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></template>
                        <span x-show="step < 3">3</span>
                    </div>
                    <p class="text-xs mt-2 font-medium text-center" :class="step === 3 ? 'text-violet-600' : (step > 3 ? 'text-green-600' : '')" style="color:var(--text-muted)">Simpan</p>
                    <p class="text-xs" style="color:var(--text-muted)">Database</p>
                </div>
            </div>

            <!-- Status Message -->
            <div class="page-card p-6 text-center">
                <p x-show="!stepError" class="font-medium progress-pulse" style="color:var(--text-secondary)" x-text="statusMessage"></p>
                <div x-show="stepError" class="text-left">
                    <div class="flex items-start gap-3 mb-4" style="color:#dc2626">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="font-semibold mb-1">Terjadi Kesalahan di Langkah <span x-text="step"></span></p>
                            <p class="text-sm" style="color:#ef4444" x-text="errorMessage"></p>
                        </div>
                    </div>
                    <button @click="state = 'menu'; stepError = false; step = 0;"
                            class="w-full font-medium py-2.5 rounded-xl transition text-sm surface-card" style="color:var(--text-secondary)">
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('audioRecorder', () => ({
        // UI State
        state: 'menu',          // menu | record | processing
        step: 0,                // 1=Whisper, 2=Gemini, 3=Save
        stepError: false,
        statusMessage: '',
        errorMessage: '',

        // Recording state
        isRecording: false,
        audioChunks: [],
        mediaRecorder: null,
        startTime: null,
        timerInterval: null,
        timerText: '00:00:00',

        // Canvas
        audioContext: null,
        analyser: null,
        dataArray: null,
        animationId: null,
        canvas: null,
        canvasCtx: null,

        // Config dari server (diisi saat init)
        whisperUrl: '{{ env("WHISPER_URL", "http://127.0.0.1:8001/transcribe") }}',
        geminiUrl: 'https://api.siputzx.my.id/api/ai/gemini',
        saveUrl: '{{ route("audio.save") }}',
        saveRawUrl: '{{ route("audio.save-raw") }}',
        csrfToken: '{{ csrf_token() }}',

        // Gemini prompt system (sama persis seperti GeminiNotulensiSummarizerService.php)
        geminiPrompt: `Kamu adalah sekretaris dan pembuat notulensi rapat profesional. Tugasmu adalah menganalisis transkrip percakapan rapat yang diberikan dalam bahasa Indonesia dan menyusun notulensi yang sangat rapi.
Keluarkan hasil analisis dalam format JSON murni tanpa membungkusnya dengan tag markdown seperti \`\`\`json atau tanda kutip tambahan lainnya. Respons kamu harus berupa string JSON valid yang dapat langsung diparse dengan json_decode di PHP.

Struktur JSON yang WAJIB kamu ikuti adalah:
{
  "ringkasan": "Ringkasan eksekutif jalannya rapat secara ringkas namun padat dan jelas (5-10 kalimat).",
  "topik_dibahas": [
    "Topik ke-1 yang dibahas...",
    "Topik ke-2..."
  ],
  "keputusan": [
    "Keputusan rapat ke-1...",
    "Keputusan rapat ke-2..."
  ],
  "action_items": [
    {
      "task": "Detail tugas yang harus dikerjakan",
      "pic": "Nama orang atau tim yang bertanggung jawab (isi '-' jika tidak disebutkan)",
      "deadline": "Batas waktu pengerjaan tugas (isi '-' jika tidak disebutkan)"
    }
  ],
  "risiko_catatan": [
    "Risiko, kendala, atau catatan penting tambahan ke-1...",
    "Risiko, kendala, atau catatan penting tambahan ke-2..."
  ]
}`,

        init() {
            this.canvas = document.getElementById('visualizerCanvas');
            if (this.canvas) {
                this.canvasCtx = this.canvas.getContext('2d');
                this.resizeCanvas();
                window.addEventListener('resize', () => this.resizeCanvas());
            }
        },

        resizeCanvas() {
            if (this.canvas && this.canvas.parentElement) {
                this.canvas.width = this.canvas.parentElement.clientWidth;
                this.canvas.height = this.canvas.parentElement.clientHeight;
                this.drawFlatLine();
            }
        },

        getStepClass(n) {
            if (this.step === n && this.stepError) return 'error';
            if (this.step === n) return 'active';
            if (this.step > n) return 'done';
            return 'idle';
        },

        // ========================
        // RECORDING
        // ========================
        async toggleRecording() {
            this.isRecording ? this.stopRecording() : await this.startRecording();
        },

        async startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                this.analyser = this.audioContext.createAnalyser();
                this.source = this.audioContext.createMediaStreamSource(stream);
                this.source.connect(this.analyser);
                this.analyser.fftSize = 2048;
                this.dataArray = new Uint8Array(this.analyser.frequencyBinCount);
                this.drawVisualizer();

                this.mediaRecorder = new MediaRecorder(stream);
                this.audioChunks = [];
                this.mediaRecorder.ondataavailable = e => { if (e.data.size > 0) this.audioChunks.push(e.data); };
                this.mediaRecorder.start();
                this.isRecording = true;
                this.startTime = Date.now();
                this.timerInterval = setInterval(() => this.updateTimer(), 1000);
            } catch (err) {
                alert('Tidak bisa mengakses mikrofon. Pastikan Anda telah memberikan izin.');
            }
        },

        stopRecording() {
            if (!this.mediaRecorder) return;
            this.mediaRecorder.stop();
            this.mediaRecorder.stream.getTracks().forEach(t => t.stop());
            this.isRecording = false;
            clearInterval(this.timerInterval);
            if (this.animationId) cancelAnimationFrame(this.animationId);
            setTimeout(() => this.drawFlatLine(), 50);
            if (this.audioContext) this.audioContext.close();
        },

        cancelRecording() {
            if (this.isRecording) this.stopRecording();
            this.state = 'menu';
            this.audioChunks = [];
            this.timerText = '00:00:00';
        },

        updateTimer() {
            const diff = new Date(Date.now() - this.startTime);
            const hh = String(diff.getUTCHours()).padStart(2, '0');
            const mm = String(diff.getUTCMinutes()).padStart(2, '0');
            const ss = String(diff.getUTCSeconds()).padStart(2, '0');
            this.timerText = `${hh}:${mm}:${ss}`;
        },

        // ========================
        // FILE UPLOAD TRIGGER
        // ========================
        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            await this.processAudio(file, file.name);
        },

        // ========================
        // MAIN PIPELINE
        // ========================
        async processAudio(audioBlob, filename) {
            this.state = 'processing';
            this.step = 0;
            this.stepError = false;
            this.errorMessage = '';

            let transcript = '';
            let notulensiJson = '';
            let liveAudioId = null;

            // ---------------------------
            // STEP 0: Simpan Audio Mentah ke Laravel DB
            // ---------------------------
            try {
                this.statusMessage = 'Menyimpan rekaman audio mentah...';

                const formData = new FormData();
                formData.append('audio', audioBlob, filename);

                const res = await fetch(this.saveRawUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken },
                    body: formData,
                });

                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.message ?? `HTTP ${res.status}`);
                }
                liveAudioId = data.id;
            } catch (err) {
                this.stepError = true;
                this.errorMessage = `Gagal menyimpan audio mentah: ${err.message}`;
                return;
            }

            // ---------------------------
            // STEP 1: Whisper (Python BE)
            // ---------------------------
            try {
                this.step = 1;
                this.statusMessage = 'Mengirim audio ke Whisper untuk transkripsi...';

                const formData = new FormData();
                formData.append('file', audioBlob, filename);

                const res = await fetch(this.whisperUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!res.ok) {
                    const errText = await res.text();
                    throw new Error(`HTTP ${res.status}: ${errText.substring(0, 200)}`);
                }

                const data = await res.json();
                // Python Whisper biasanya return { text: "..." } atau { transcription: "..." }
                transcript = data.text ?? data.transcription ?? data.result ?? '';

                if (!transcript || transcript.trim() === '') {
                    throw new Error('Whisper mengembalikan teks kosong. Pastikan audio berisi suara yang jelas.');
                }
            } catch (err) {
                this.stepError = true;
                this.errorMessage = `Gagal transkripsi Whisper: ${err.message}`;
                return;
            }

            // ---------------------------
            // STEP 2: Gemini AI
            // ---------------------------
            try {
                this.step = 2;
                this.statusMessage = 'Membuat notulensi dengan Gemini AI...';

                // Gemini API dari GeminiNotulensiSummarizerService — pakai GET dengan params
                const cookie = Math.random().toString(36).substring(2, 18);
                const params = new URLSearchParams({
                    text: transcript,
                    cookie: cookie,
                    promptSystem: this.geminiPrompt,
                });

                const res = await fetch(`${this.geminiUrl}?${params.toString()}`, {
                    method: 'GET',
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status} dari Gemini API`);
                }

                const data = await res.json();
                let responseText = data?.data?.response ?? '';

                if (!responseText) {
                    throw new Error('Gemini tidak mengembalikan hasil. Coba lagi.');
                }

                // Bersihkan markdown wrapper jika ada (``` json ... ```)
                responseText = responseText.trim();
                const mdMatch = responseText.match(/^```(?:json)?\s*([\s\S]*?)\s*```$/);
                if (mdMatch) responseText = mdMatch[1].trim();

                // Validasi JSON
                const parsed = JSON.parse(responseText);
                notulensiJson = JSON.stringify(parsed);

            } catch (err) {
                this.stepError = true;
                if (err instanceof SyntaxError) {
                    this.errorMessage = 'Gemini mengembalikan format yang tidak valid (bukan JSON). Coba lagi.';
                } else {
                    this.errorMessage = `Gagal generate notulensi: ${err.message}`;
                }
                return;
            }

            // ---------------------------
            // STEP 3: Simpan ke Laravel DB
            // ---------------------------
            try {
                this.step = 3;
                this.statusMessage = 'Menyimpan notulensi ke database...';

                const formData = new FormData();
                formData.append('live_audio_id', liveAudioId);
                formData.append('notulensi_teks', notulensiJson);

                const res = await fetch(this.saveUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken },
                    body: formData,
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message ?? `HTTP ${res.status}`);
                }

                // Sukses! Redirect ke halaman detail
                this.statusMessage = 'Berhasil! Mengarahkan ke halaman notulensi...';
                window.location.href = data.redirect_url;

            } catch (err) {
                this.stepError = true;
                this.errorMessage = `Gagal menyimpan: ${err.message}`;
            }
        },

        // ========================
        // CANVAS
        // ========================
        drawVisualizer() {
            if (!this.analyser || !this.isRecording) return;
            this.animationId = requestAnimationFrame(() => this.drawVisualizer());
            this.analyser.getByteTimeDomainData(this.dataArray);
            this.canvasCtx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.canvasCtx.lineWidth = 3;
            this.canvasCtx.strokeStyle = '#7c3aed';
            this.canvasCtx.lineCap = 'round';
            this.canvasCtx.beginPath();
            const sliceWidth = this.canvas.width / this.dataArray.length;
            let x = 0;
            for (let i = 0; i < this.dataArray.length; i++) {
                const v = this.dataArray[i] / 128.0;
                const dist = Math.abs((i / this.dataArray.length) - 0.5) * 2;
                const mul = Math.max(0, 1 - dist);
                const y = ((v - 1) * mul + 1) * this.canvas.height / 2;
                i === 0 ? this.canvasCtx.moveTo(x, y) : this.canvasCtx.lineTo(x, y);
                x += sliceWidth;
            }
            this.canvasCtx.lineTo(this.canvas.width, this.canvas.height / 2);
            this.canvasCtx.stroke();
        },

        drawFlatLine() {
            if (!this.canvasCtx) return;
            this.canvasCtx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.canvasCtx.lineWidth = 2;
            this.canvasCtx.strokeStyle = 'rgba(128,128,128,0.15)';
            this.canvasCtx.beginPath();
            this.canvasCtx.moveTo(0, this.canvas.height / 2);
            this.canvasCtx.lineTo(this.canvas.width, this.canvas.height / 2);
            this.canvasCtx.stroke();
        },
    }));
});
</script>
@endsection