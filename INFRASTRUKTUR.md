# Infrastruktur & Arsitektur Aplikasi

Secara umum, aplikasi ini adalah **sistem meeting dan notulensi otomatis berbasis AI** dengan arsitektur **microservices hybrid**:

```
┌──────────── Browser ────────────┐
│  LiveKit Client  │  Echo (WS)   │
│  Alpine.js       │  Axios       │
└──────────┬──────────────────────┘
           │
    ┌──────┼──────────┬────────┐
    ▼      ▼          ▼        ▼
 LiveKit   Laravel   Reverb   Whisper
 Server    (PHP)     (WS)     Python
 :7880     :8000     :8080    :8001
           │
    ┌──────┴──────┐
    ▼             ▼
 MySQL      OpenAI API /
            Gemini API
```

**5 service yang berjalan bersamaan** (dijalankan oleh `activate.bat`):
1. **Laravel** (`php artisan serve :8000`) — backend utama
2. **Queue Worker** (`php artisan queue:work`) — proses pipeline notulensi
3. **Reverb** (`php artisan reverb:start :8080`) — WebSocket signaling
4. **LiveKit Server** (`livekit-server.exe :7880`) — SFU WebRTC
5. **Whisper Server** (Python/FastAPI `:8001`) — transkripsi lokal

---

## 1. Bagaimana Meeting Bekerja

Meeting adalah **room virtual berbasis WebRTC** yang menggunakan **LiveKit** sebagai SFU (Selective Forwarding Unit).

**Alur:**
1. User buat/join meeting → Laravel simpan ke tabel `meetings`
2. Laravel generate **JWT token** untuk LiveKit (HMAC-SHA256, expired 1 jam)
3. Browser connect ke LiveKit server via WebSocket (`:7880`)
4. LiveKit handle distribusi **audio/video/screen share** antar peserta
5. **Reverb (Echo)** digunakan sebagai signaling tambahan (screen share events, recording status, camera toggle)
6. Peserta bisa pin video, pagination (4 per page), screen share, dan indicator berbicara

**Key files:**
- `MeetingController.php` — logic create/join/room/token
- `resources/views/meeting/room.blade.php` — halaman room dengan LiveKit client JS
- `routes/web.php` — definisi route meeting

---

## 2. Flow User Masuk ke Room Sampai Bertemu User Lain

```
1. User buka /join → halaman join.blade.php
2. Pilih "Buat Rapat Baru" atau "Gabung dengan ID"
3. POST /meeting/create atau POST /meeting/join → MeetingController
   ├── Validasi input
   ├── Simpan Meeting ke DB
   ├── Panggil enterMeeting() → insert ke meeting_participants
   └── Redirect ke /meeting/room/{id}

4. Room page load (room.blade.php):
   ├── Inline script setup Reverb config + Echo connection (WS :8080)
   ├── connectToLiveKit():
   │   ├── POST /meeting/{id}/livekit-token → generate JWT
   │   ├── new LiveKit.Room({adaptiveStream, dynacast, h720})
   │   ├── room.connect(liveKitUrl, token) → WebSocket ke :7880
   │   ├── Publish local camera + mic tracks
   │   └── Listen event:
   │       ├── TrackSubscribed → render remote participant video
   │       ├── ParticipantDisconnected → hapus video card
   │       ├── ActiveSpeakersChanged → highlight speaking ring
   │       └── ConnectionStateChanged → handle reconnect
   └── subscribeEchoChannel() → private channel meeting.{id}

5. User lain join → LiveKit handle otomatis
   ├── Participant baru muncul di room
   ├── TrackSubscribed → createRemoteVideoCard() → tampil di grid
   └── Pagination dots render jika > 4 peserta
```

---

## 3. Fungsi LiveKit Server

LiveKit Server adalah **Selective Forwarding Unit (SFU)** untuk WebRTC. Fungsinya:

| Fungsi | Detail |
|--------|--------|
| **Routing Media** | Menerima audio/video dari publisher, forward ke subscriber yang tepat |
| **Room Management** | Create/join/leave room, track participant list |
| **Adaptive Streaming** | Simulcast + Dynacast — kirim multiple resolusi, client pilih sesuai bandwidth |
| **Screen Share** | Forward screen capture track ke semua peserta |
| **Speaking Detection** | Deteksi siapa yang bicara via audio level |
| **Token Auth** | Verifikasi JWT yang digenerate Laravel |

LiveKit **tidak** handle transkripsi atau notulensi — itu dilakukan oleh Whisper Python server dan AI service secara terpisah.

**Konfigurasi**: `docker/livekit.yaml`:
```yaml
port: 7880
keys: { devkey: secret }
rtc: { tcp_port: 7881, udp_port: 7882 }
```

---

## 4. Flow User Mendapatkan Notulensi

Ada **2 jalur** mendapatkan notulensi:

### Jalur A: Notulensi dari Meeting (Recording + Pipeline Background)
```
User klik "Rekam" → rekam audio via LiveKit
  → Upload file → POST /meeting/{id}/recording
  → MeetingAiPipelineDispatcher::startFromRekaman()
  → Bus::chain([4 jobs]):
      1. ExtractAudioFromRecordingJob
         - FFmpeg extract audio → MP3
      2. TranscribeRecordingJob
         - OpenAITranscriptionService → OpenAI Whisper API
         - Simpan ke tabel transkrips
      3. SummarizeTranscriptToNotulensiJob
         - OpenAINotulensiSummarizerService → OpenAI GPT
         - Simpan ke tabel notulensis
      4. GenerateNotulensiPdfJob
         - DomPDF generate PDF
         - Simpan file_pdf, buat arsip
  → Pipeline status: idle → processing → completed
  → User download PDF via /meeting/{id}/notulensi-pdf
```

### Jalur B: Live Transcription + Gemini (On-demand)
```
User klik "AI Notulen" → POST /meeting/{id}/start-recording
  → startLiveTranscription():
      ├── WebSocket ke Whisper Python (:8001/ws/transcribe)
      ├── ScriptProcessor + VAD (Voice Activity Detection)
      ├── Kirim PCM 16kHz saat ada suara
      ├── Whisper transcribe → kirim balik text
      ├── Tampilkan di sidebar transkrip
      └── Simpan via POST /meeting/{id}/save-live-transcript

User klik "Simpan Notulen" → triggerGeminiNotulensi():
  ├── POST /meeting/{id}/generate-notulensi
  ├── GeminiNotulensiSummarizerService → free Gemini API
  ├── Simpan structured_summary JSON ke notulensis
  ├── Generate PDF via DomPDF
  └── Tampilkan modal notulensi
```

### Jalur C: Audio Notulensi Standalone (`/audio`)
```
User buka /audio → audio/index.blade.php (Alpine.js)
  ├── Rekam langsung (MediaRecorder) atau Upload file
  └── Pipeline frontend (3 step):
      1. POST /audio/save-raw → simpan audio ke live_audios
      2. POST ke Whisper Python (:8001/transcribe) → dapat transcript
      3. GET ke Gemini API (siputzx) → dapat notulensi JSON
      4. POST /audio/save → simpan ke DB
  → Redirect ke /audio/{id}
```

---

## 5. Flow Sistem User Merekam Suara Sampai Mendapatkan Notulensi

Berikut alur **end-to-end** dari suara masuk sampai notulensi jadi:

### Skenario: Live Transcription di Meeting Room

```
[Browser]                         [LiveKit]               [Whisper:8001]    [Laravel]      [Gemini API]
    │                                │                        │                │               │
    ├─ Mic → WebRTC track ─────────►│                        │                │               │
    │                                │                        │                │               │
    ├─ Klik "AI Notulen"            │                        │                │               │
    ├─ POST /start-recording ──────►│                        │                │               │
    │◄─ Clear transcript            │                        │                │               │
    │                                │                        │                │               │
    ├─ startLiveTranscription()     │                        │                │               │
    ├─ WebSocket connect ──────────►│──────────────────────►│                │               │
    │                                │                        │                │               │
    ├─ ScriptProcessor onaudioprocess (4096 samples)         │                │               │
    │  ├─ VAD: hitung energy        │                        │                │               │
    │  ├─ Jika energy > threshold → buffer PCM              │                │               │
    │  └─ Jika buffer ≥ 28 frame atau user diam             │                │               │
    │     └─ send Int16Array ──────►│──────────────────────►│                │               │
    │                                │                        │                │               │
    │                                │                        ├─ faster-whisper│               │
    │                                │                        │   transcribe   │               │
    │                                │                        ├─ Filter       │               │
    │                                │                        │   hallucination│               │
    │◄─ {"status":"success",text} ◄─│◄──────────────────────┤                │               │
    │                                │                        │                │               │
    ├─ appendTranscriptMessage()    │                        │                │               │
    ├─ syncTranscriptToLaravel()───►│──────────────────────►│─ POST /save-   │               │
    │                                │                        │   live-transcript│             │
    │                                │                        │   → simpan ke  │               │
    │                                │                        │   transkrips   │               │
    │                                │                        │                │               │
    ├─ Klik "Simpan Notulen"        │                        │                │               │
    ├─ POST /generate-notulensi ────┤──────────────────────►│                │               │
    │                                │                        │                │               │
    │                                │                        ├─ ambil        │               │
    │                                │                        │   transkrip   │               │
    │                                │                        │   dari DB     │               │
    │                                │                        │                │               │
    │                                │                        ├─ GET Gemini ──►│──────────────►│
    │                                │                        │   ?text=...    │               │
    │                                │                        │◄─ JSON notulen│◄──────────────┤
    │                                │                        │                │               │
    │                                │                        ├─ Simpan ke    │               │
    │                                │                        │   notulensis  │               │
    │                                │                        ├─ Generate PDF │               │
    │                                │                        │   (DomPDF)    │               │
    │◄─ {notulensi, pdf_url} ◄──────┤────────────────────────┤                │               │
    │                                │                        │                │               │
    ├─ Tampilkan modal notulensi    │                        │                │               │
    └─ Bisa download PDF            │                        │                │               │
```

### Skenario: Recording Pipeline (Background)

```
User upload rekaman → POST /meeting/{id}/recording
  → Laravel simpan file ke storage
  → Dispatcher chain 4 jobs (queue worker):
      1. Extract: FFmpeg convert ke MP3
      2. Transcribe: OpenAI Whisper API → text
      3. Summarize: OpenAI GPT → notulensi JSON
      4. Generate PDF: DomPDF → simpan file
  → User polling GET /meeting/{id}/pipeline-status
  → Status jadi "completed" → PDF siap di-download
```

### Ringkasan teknologi AI yang digunakan:

| Tahap | Service | Model |
|-------|---------|-------|
| Transkripsi (file) | OpenAI API | `whisper-1` |
| Transkripsi (live) | Python lokal | `faster-whisper base` (ID) |
| Summarization (file) | OpenAI API | `gpt-4o-mini` |
| Summarization (live) | Gemini free API | `api.siputzx.my.id` |
| PDF Generation | DomPDF | Blade template → PDF |
