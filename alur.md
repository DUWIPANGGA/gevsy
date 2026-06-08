# Arsitektur & Alur Kerja Meeting System

---

## Arsitektur Umum

```
┌─────────────────────────────────────────────────────────────┐
│                    Browser (Client)                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌────────────┐ │
│  │ LiveKit  │  │  Alpine  │  │   Echo   │  │  Tailwind  │ │
│  │  Client  │  │   JS     │  │ (Reverb) │  │   CDN      │ │
│  └──────────┘  └──────────┘  └──────────┘  └────────────┘ │
└──────────────────────┬──────────────────────────────────────┘
                       │
         ┌─────────────┼─────────────┐
         ▼             ▼             ▼
┌──────────────┐ ┌──────────┐ ┌──────────┐
│  LiveKit     │ │ Laravel  │ │ Reverb   │
│  Server      │ │ Web App  │ │ WebSocket│
│  (Docker)    │ │ (PHP)    │ │ Server   │
│  :7880 (WS)  │ │ :8000    │ │ :8080    │
└──────────────┘ └──────────┘ └──────────┘
```

---

## Alur Meeting (End-to-End)

### 1. User Masuk ke Beranda (`/meeting/join`)
- Route: `meeting.join.form` → `MeetingController@showJoinForm()`
- View: `resources/views/meeting/join.blade.php`
- User bisa memilih:
  - **Rapat Baru** → modal → form `POST /meeting/create` → `MeetingController@createMeeting()`
  - **Gabung dengan kode** → form `POST /meeting/join` → `MeetingController@joinMeeting()`

### 2. Create/Join Meeting → Redirect ke Room
- `MeetingController@enterMeeting()`:
  1. Buat/simpan meeting di DB (model `Meeting`)
  2. Generate LiveKit JWT token via `getLiveKitToken()`
  3. Redirect ke `/meeting/room/{id}` dengan token di session

### 3. Room Page (`/meeting/room/{id}`)
- View: `resources/views/meeting/room.blade.php`
- `MeetingController@room()`:
  1. Fetch meeting dari DB
  2. Generate LiveKit JWT untuk participant
  3. Pass `$token` dan `$meeting` ke view

---

## LiveKit SFU (Selective Forwarding Unit)

### Koneksi
```js
room.blade.php (JavaScript):

const room = new LiveKit.Room({
  adaptiveStream: true,
  dynacast: true,
  videoCaptureDefaults: {
    resolution: LiveKit.VideoPresets.h720,
  }
});

room.connect('ws://{hostname}:7880', token);
```

### Media Tracks
| Source | Method | Deskripsi |
|--------|--------|-----------|
| Camera | `room.localParticipant.setCameraEnabled(true)` | Video dari webcam |
| Mic | `room.localParticipant.setMicrophoneEnabled(true)` | Audio dari mic |
| Screen Share | `room.localParticipant.setScreenShareEnabled(true)` | Layar/shared window |

### Participant Events
| Event | Handler |
|-------|---------|
| `RoomEvent.TrackSubscribed` | `createRemoteVideoCard()` — render video participant |
| `RoomEvent.TrackUnsubscribed` | Hapus elemen video + cleanup `pinnedIdentities` |
| `RoomEvent.ParticipantDisconnected` | Cleanup participant dari grid + pinned |
| `RoomEvent.ActiveSpeakersChanged` | Update speaking ring indicator |
| `RoomEvent.ConnectionStateChanged` | Handle reconnect/disconnect |
| `RoomEvent.Disconnected` | Redirect ke halaman sebelumnya |

---

## Video Grid Pagination

### Layout
```
┌──────────────────────────────────────────────────────────────┐
│  [Video Grid Container — flex row]                           │
│  ┌────────────────────────────┐  ┌───────────────────────┐  │
│  │  Video Grid Main (flex:1)  │  │ Screen Share (420px)  │  │
│  │  ┌────┐ ┌────┐             │  │                       │  │
│  │  │ P1 │ │ P2 │             │  │  (hanya muncul jika   │  │
│  │  └────┘ └────┘             │  │   ada screen share)   │  │
│  │  ┌────┐ ┌────┐             │  └───────────────────────┘  │
│  │  │ P3 │ │ P4 │             │                             │
│  │  └────┘ └────┘             │                             │
│  └────────────────────────────┘                             │
│  ● ○ ○ ○ (pagination dots)                                  │
└──────────────────────────────────────────────────────────────┘
```

- `PER_PAGE = 4` — maksimal 4 tile per halaman
- `currentPage` state di Alpine
- Total halaman = `Math.ceil(totalParticipants / PER_PAGE)`
- Navigasi via dot indicators + prev/next

### Sorting Peserta (`getVisibleParticipants`)
1. Pinned participants (dari `pinnedIdentities` array) — muncul pertama di page 0
2. Sisanya diurutkan: local participant first, then remote

---

## Pin View Feature

```js
pinnedIdentities = ['user_a', 'user_b']  // Alpine state

togglePin(identity):
  if pinned: hapus dari array
  else: tambah ke array

getVisibleParticipants():
  1. Ambil pinned identities → map ke participant objects
  2. Ambil unpinned participants → filter out pinned
  3. Concatenate: pinned + unpinned
  4. Hitung page berdasarkan index
```

- **Pin via button** (📌) di remote video cards
- **Pin via context menu** (right-click) di sidebar participant list
- Pinned cards punya class `pinned-card` (golden border + glow)
- Pin button aktif: class `active` + icon beda
- Cleanup: `TrackUnsubscribed` dan `ParticipantDisconnected` hapus dari `pinnedIdentities`

---

## Screen Share

```
Alur:
  1. User klik "Bagikan Layar" → room.localParticipant.setScreenShareEnabled(true)
  2. LiveKit server distribusi track screen share ke semua peserta
  3. RoomEvent.TrackSubscribed dengan source === Track.Source.ScreenShare
  4. Render di screenShareContainer (flex sibling videoGridMain)
  5. Broadcast ke peserta lain via Echo (screen-share-started, sender_id)
  6. Listener: skip broadcast dari diri sendiri (cek sender_id)

  Takeover:
  - Jika sudah ada yang share, confirm dialog
  - Yang lama di-stop, yang baru mulai
```

---

## Speaking Indicator

```
RoomEvent.ActiveSpeakersChanged → speakers[]

Untuk setiap participant:
  - Cek apakah identity ada di speakers[]
  - Jika ya: tambah class "speaking-ring" (pulse animation hijau)
  - Jika tidak: hapus class "speaking-ring"
```

---

## Camera/Mic Persistence (localStorage)

```
Key: "deviceState_{meetingId}"
Value: JSON { camera: bool, mic: bool }

- On load: baca dari localStorage, set state sesuai
- On toggle: update localStorage
- On unload: simpan state terakhir
```

---

## Echo / Reverb (WebSocket)

```
Echo (Laravel Reverb):
  - connect ke Reverb server (:8080)
  - Channel: "meeting.{meetingId}"
  - Events:
    - screen-share-started / screen-share-stopped
    - recording-status
    - transcription-update

Init (echo.js):
  window._REVERB_CONFIG (dari inline script) atau VITE env
```

---

## Notulensi (Transkripsi & Ringkasan AI)

### 1. Rekaman Audio (`/audio`)
```
Audio Index (audio/index.blade.php):
  - Upload file atau rekam langsung (MediaRecorder API)
  - Pipeline: Upload → Whisper (transkripsi) → Gemini AI (notulensi) → Save ke DB

Pipeline di frontend (Alpine audioRecorder):
  Step 0: Save raw audio ke Laravel (POST /audio/save-raw)
  Step 1: Kirim ke Whisper (Python BE :8001) → transcript text
  Step 2: Kirim transcript ke Gemini API → JSON notulensi
  Step 3: Simpan JSON ke database (POST /audio/save)
  Redirect ke /audio/{id}
```

### 2. Live Transcription (via LiveKit)
```
room.blade.php:
  - Setup transcription: room.setTranscription(true)
  - RoomEvent.TranscriptionReceived → tampilkan di sidebar
  - Kirim transcript partial ke server via Echo untuk disimpan
```

### 3. Generate Notulensi dari Meeting (`MeetingController@generateLiveNotulensi`)
```
  - Collect transkrip dari DB
  - Kirim ke GeminiNotulensiSummarizerService
  - Simpan hasil JSON ke tabel Notulensi
  - Logging: storage/logs/laravel.log
```

---

## RBAC (Role-Based Access Control)

```
Spatie Permission + Filament Shield:

Permission (custom, 11 item):
  meeting.access, meeting.create, meeting.join, meeting.record,
  meeting.transcribe, meeting.screen-share, meeting.manage-participants,
  meeting.end, meeting.view-agenda, notulensi.view, notulensi.edit

Middleware: CheckUserPermission
  - Route group: user.permission:{permission}
  - Cek user.hasPermissionTo(permission) via Spatie

Roles:
  - super_admin (all access via Filament)
  - admin (custom permissions)
  - user (custom permissions)
```

---

## Struktur Database (Model & Relasi)

```
User (id, name, email, password, jabatan)
  │
  ├── Meeting (id, nama_rapat, tipe, tanggal, waktu, status_rapat, host_id)
  │     │
  │     ├── MeetingParticipant (id, meeting_id, user_id, joined_at, left_at)
  │     │
  │     └── Notulensi (id, meeting_id, content_json, generated_at)
  │
  └── LiveAudio (id, user_id, file_path, file_size_bytes, mime_type, notulensi_teks, tanggal_rekam)
        │
        └── Transkrip (id, live_audio_id, konten, speaker, created_at)
```

---

## Route Structure

| Method | URI | Controller@Method | Middleware |
|--------|-----|-------------------|------------|
| GET | `/meeting` | `MeetingController@showJoinForm` | `user.permission:meeting.access` |
| POST | `/meeting/create` | `MeetingController@createMeeting` | `user.permission:meeting.create` |
| POST | `/meeting/join` | `MeetingController@joinMeeting` | `user.permission:meeting.join` |
| GET | `/meeting/room/{id}` | `MeetingController@room` | `user.permission:meeting.join` |
| POST | `/meeting/broadcast` | `MeetingController@broadcastSignal` | auth |
| POST | `/meeting/{id}/notulensi` | `MeetingController@generateLiveNotulensi` | `user.permission:notulensi.edit` |
| GET | `/agenda` | `MeetingController@agenda` | `user.permission:meeting.view-agenda` |
| GET | `/profile` | `ProfileController@show` | auth |
| GET/POST | `/audio/*` | Audio CRUD controllers | `user.permission:notulensi.*` |

---

## Alur Lengkap dari A-Z

```
1. User login → redirected ke /meeting (beranda)
2. Klik "Rapat Baru" → modal → isi form → POST /meeting/create
3. MeetingController:
   a. Validasi input
   b. Simpan Meeting ke DB
   c. Generate JWT token via getLiveKitToken()
   d. Simpan token di session
   e. Redirect ke /meeting/room/{id}
4. Room page load:
   a. Inline script setup window._REVERB_CONFIG
   b. Connect Echo (Reverb WebSocket)
   c. Connect LiveKit (WebSocket ke :7880)
   d. Alpine init:
      - Cek localStorage buat camera/mic state
      - Request camera/mic
      - Render local participant
      - Listen untuk remote participants
5. Remote user join:
   a. Buka link /meeting/room/{id}
   b. LiveKit handle participant join
   c. TrackSubscribed → render video card
6. Screen share:
   a. Klik "Bagikan Layar"
   b. Confirm kalau ada yang sudah share
   c. setScreenShareEnabled(true)
   d. Broadcast via Echo
7. Recording:
   a. Klik "Rekam"
   b. LiveKit recording API
   c. Transkrip via Whisper/Gemini
8. Leave:
   a. Disconnect LiveKit
   b. Cleanup
   c. Redirect ke beranda
```
