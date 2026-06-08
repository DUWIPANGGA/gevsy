@extends('layouts.room')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        * { font-family: 'Inter', system-ui, sans-serif; }

        /* ── Theme variables ── */
        :root {
            --meeting-bg: radial-gradient(ellipse 80% 60% at 20% 80%, rgba(139,92,246,0.08) 0%, transparent 60%),
                           radial-gradient(ellipse 60% 50% at 80% 20%, rgba(6,182,212,0.06) 0%, transparent 50%),
                           radial-gradient(ellipse 50% 40% at 50% 50%, rgba(236,72,153,0.05) 0%, transparent 40%),
                           linear-gradient(160deg, #d4d6dd 0%, #c8cad2 50%, #c0c2ca 100%);
            --grid-bg: rgba(255,255,255,0.55);
            --grid-border: rgba(0,0,0,0.06);
            --card-bg: rgba(255,255,255,0.78);
            --card-border: rgba(0,0,0,0.08);
            --card-shadow: 0 8px 32px rgba(0,0,0,0.12);
            --screen-share-bg: rgba(240,242,245,0.85);
            --toolbar-bg: rgba(230,232,238,0.88);
            --toolbar-border: rgba(0,0,0,0.06);
            --sidebar-bg: rgba(230,232,238,0.92);
            --sidebar-border: rgba(0,0,0,0.06);
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --name-label-bg: rgba(0,0,0,0.08);
            --rec-bg: linear-gradient(135deg, #ef4444, #dc2626);
            --meeting-container-bg: rgba(0,0,0,0.05);
        }
        :root.dark, .dark {
            --meeting-bg: radial-gradient(ellipse 80% 60% at 20% 80%, rgba(139,92,246,0.12) 0%, transparent 60%),
                           radial-gradient(ellipse 60% 50% at 80% 20%, rgba(6,182,212,0.08) 0%, transparent 50%),
                           radial-gradient(ellipse 50% 40% at 50% 50%, rgba(236,72,153,0.05) 0%, transparent 40%),
                           linear-gradient(160deg, #0f0f13 0%, #1a1a25 50%, #0d0d12 100%);
            --grid-bg: rgba(0,0,0,0.35);
            --grid-border: rgba(255,255,255,0.05);
            --card-bg: rgba(0,0,0,0.6);
            --card-border: rgba(255,255,255,0.06);
            --card-shadow: 0 8px 32px rgba(0,0,0,0.4);
            --screen-share-bg: rgba(0,0,0,0.7);
            --toolbar-bg: rgba(12,12,20,0.85);
            --toolbar-border: rgba(255,255,255,0.04);
            --sidebar-bg: rgba(15,15,25,0.92);
            --sidebar-border: rgba(255,255,255,0.04);
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --name-label-bg: rgba(0,0,0,0.5);
            --rec-bg: linear-gradient(135deg, #ef4444, #dc2626);
            --meeting-container-bg: rgba(0,0,0,0.35);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb, rgba(139,92,246,0.3)); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--scrollbar-thumb-hover, rgba(139,92,246,0.5)); }

        #transcriptSidebar.collapsed {
            width: 0px !important;
            border-left-width: 0px !important;
            opacity: 0;
            pointer-events: none;
        }

        /* ── Animated background ── */
        .meeting-bg { background: var(--meeting-bg); }

        /* ── Glass card base ── */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
            transform: translateZ(0);
        }
        .glass-card:hover {
            border-color: rgba(139,92,246,0.2);
            box-shadow: 0 12px 48px rgba(0,0,0,0.4), 0 0 20px rgba(139,92,246,0.05);
        }

        /* ── Video cards ── */
        .video-card {
            background: var(--card-bg);
            backdrop-filter: blur(8px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s cubic-bezier(0.34,1.56,0.64,1), border-color 0.3s cubic-bezier(0.34,1.56,0.64,1);
            overflow: hidden;
            transform: translateZ(0);
        }
        .video-card:hover {
            transform: translateY(-4px) scale(1.01);
            border-color: rgba(139,92,246,0.25);
            box-shadow: 0 16px 48px rgba(0,0,0,0.5), 0 0 30px rgba(139,92,246,0.08);
        }
        .video-card.pinned-card {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.5), 0 16px 48px rgba(0,0,0,0.5), 0 0 40px rgba(245,158,11,0.1);
        }

        /* ── Video grid wrapper ── */
        .video-grid-container {
            background: var(--meeting-container-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--grid-border);
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.05), 0 0 60px rgba(139,92,246,0.03);
            transform: translateZ(0);
        }

        /* ── Screen share ── */
        .screen-share-container {
            border-radius: 20px;
            background: var(--screen-share-bg);
            backdrop-filter: blur(8px);
            border: 1px solid var(--card-border);
            box-shadow: 0 8px 40px rgba(0,0,0,0.5);
        }

        /* ── Toolbar ── */
        .bottom-toolbar {
            background: var(--toolbar-bg);
            backdrop-filter: blur(20px) saturate(1.4);
            -webkit-backdrop-filter: blur(20px) saturate(1.4);
            border-top: 1px solid var(--toolbar-border);
            box-shadow: 0 -8px 40px rgba(0,0,0,0.5);
        }
        .toolbar-btn {
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), filter 0.3s, opacity 0.3s;
            position: relative;
        }
        .toolbar-btn::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 16px;
            background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(139,92,246,0.15), transparent 70%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .toolbar-btn:hover::after { opacity: 1; }
        .toolbar-btn:hover {
            transform: translateY(-4px);
        }
        .toolbar-btn:active {
            transform: translateY(-1px) scale(0.96);
        }
        .toolbar-btn svg {
            filter: drop-shadow(0 2px 8px rgba(139,92,246,0));
            transition: filter 0.3s;
        }
        .toolbar-btn:hover svg {
            filter: drop-shadow(0 2px 12px rgba(139,92,246,0.3));
        }

        /* ── Sidebar ── */
        .participant-sidebar {
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px) saturate(1.3);
            -webkit-backdrop-filter: blur(20px) saturate(1.3);
            border-left: 1px solid var(--sidebar-border);
            box-shadow: -8px 0 40px rgba(0,0,0,0.4);
            transform: translateZ(0);
        }
        .sidebar-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 14px;
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), background 0.25s, border-color 0.25s, box-shadow 0.25s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transform: translateZ(0);
        }
        .sidebar-item:hover {
            background: rgba(139,92,246,0.08);
            border-color: rgba(139,92,246,0.2);
            transform: translateX(6px);
            box-shadow: 0 4px 16px rgba(139,92,246,0.1);
        }

        /* ── Pagination dots ── */
        .pagination-dot {
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .pagination-dot:hover {
            transform: scale(1.4);
        }

        /* ── Top bar ── */
        .top-bar {
            background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, transparent 100%);
            backdrop-filter: blur(8px);
            transform: translateZ(0);
        }

        /* ── Glow button (Akhiri) ── */
        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(220,38,38,0.4);
        }
        .btn-danger:active {
            transform: translateY(0) scale(0.97);
        }

        /* ── Pin button ── */
        .pin-btn {
            background: rgba(55,65,81,0.7);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.06);
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
            border-radius: 8px;
        }
        .pin-btn:hover {
            background: rgba(55,65,81,0.9);
            transform: scale(1.1);
        }
        .pin-btn.active {
            background: #eab308;
            color: #000;
            box-shadow: 0 0 20px rgba(234,179,8,0.3);
        }

        /* ── Name label ── */
        .name-label {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 8px;
        }

        /* ── Speaking ring animation ── */
        @keyframes speak-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0.4); }
            50% { box-shadow: 0 0 0 8px rgba(34,197,94,0), 0 0 20px 4px rgba(34,197,94,0.15); }
        }
        .speaking-ring {
            animation: speak-pulse 1.2s ease-in-out infinite !important;
            border: 3px solid #22c55e !important;
            box-shadow: 0 0 20px rgba(34,197,94,0.2) !important;
            transform: translateZ(0);
        }

        /* ── REC badge ── */
        .rec-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 2px 12px rgba(239,68,68,0.3);
            border-radius: 6px;
            animation: rec-pulse 2s ease-in-out infinite;
        }
        @keyframes rec-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* ── Context menu ── */
        .context-menu {
            background: var(--dropdown-bg, rgba(20,20,30,0.95));
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border, rgba(255,255,255,0.06));
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            border-radius: 12px;
            overflow: hidden;
            transform: translateZ(0);
        }
        .context-menu button {
            transition: background 0.15s;
        }
        .context-menu button:hover {
            background: rgba(139,92,246,0.15);
        }

        /* ── Modal ── */
        .modal-glass {
            background: var(--dropdown-bg, rgba(20,20,30,0.95));
            backdrop-filter: blur(24px);
            border: 1px solid var(--glass-border, rgba(255,255,255,0.06));
            box-shadow: 0 16px 64px rgba(0,0,0,0.5);
            border-radius: 20px;
            transform: translateZ(0);
        }
        .modal-glass button {
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .modal-glass button:hover {
            transform: translateY(-1px);
        }

        /* ── Layout modes ── */
        #videoGridMain.layout-speaker { display: flex; flex-direction: column; }
        #videoGridMain.layout-speaker .speaker-main-video { flex: 1; min-height: 0; }
        #videoGridMain.layout-speaker .speaker-strip { display: flex; gap: 8px; padding: 4px; height: 140px; overflow-x: auto; flex-shrink: 0; background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05); }
        #videoGridMain.layout-speaker .speaker-strip .video-card { min-width: 180px; height: 128px; flex-shrink: 0; }
        #videoGridMain.layout-speaker .speaker-strip .video-card.speaking-ring { border-color: #22c55e !important; }
        #videoGridMain.layout-speaker .video-card:not(.speaker-main-video):not(.speaker-strip .video-card) { display: none; }

        #videoGridMain.layout-sidebar { display: flex; flex-direction: row; }
        #videoGridMain.layout-sidebar .sidebar-main-area { flex: 1; min-width: 0; display: grid; gap: 2px; }
        #videoGridMain.layout-sidebar .sidebar-vertical-strip { width: 200px; flex-shrink: 0; display: flex; flex-direction: column; gap: 4px; padding: 4px; overflow-y: auto; background: rgba(0,0,0,0.2); border-left: 1px solid rgba(255,255,255,0.05); }
        #videoGridMain.layout-sidebar .sidebar-vertical-strip .video-card { min-height: 120px; flex-shrink: 0; }
        #videoGridMain.layout-sidebar .video-card:not(.sidebar-main-area .video-card):not(.sidebar-vertical-strip .video-card) { display: none; }

        #videoGridMain.layout-spotlight { position: relative; }
        #videoGridMain.layout-spotlight .video-card.spotlight-main { position: absolute; inset: 0; z-index: 1; margin: 0; border-radius: 0; }
        #videoGridMain.layout-spotlight .video-card.spotlight-overlay { position: absolute; z-index: 2; width: 180px; height: 120px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.6); cursor: pointer; transition: transform 0.3s; }
        #videoGridMain.layout-spotlight .video-card.spotlight-overlay:hover { transform: scale(1.05); z-index: 3; }
        #videoGridMain.layout-spotlight .video-card:not(.spotlight-main):not(.spotlight-overlay) { display: none; }

        /* Layout selector dropdown */
        .layout-dropdown { background: var(--dropdown-bg, rgba(20,20,30,0.95)); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.5); overflow: hidden; z-index: 60; transform: translateZ(0); }
        .layout-dropdown button { transition: background 0.15s, color 0.15s; display: flex; align-items: center; gap: 10px; padding: 10px 16px; width: 100%; text-align: left; font-size: 13px; color: #d1d5db; }
        .layout-dropdown button:hover { background: rgba(139,92,246,0.15); color: #fff; }
        .layout-dropdown button.active-layout { background: rgba(139,92,246,0.12); color: #a78bfa; font-weight: 600; border-left: 3px solid #a78bfa; }

        /* ── Entry animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .video-card {
            animation: fadeInScale 0.4s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .sidebar-item {
            animation: fadeInUp 0.3s ease-out both;
        }
    </style>

<div class="h-screen flex flex-col relative meeting-bg text-white overflow-hidden font-sans">
        
        <!-- Top Bar -->
        <div class="absolute top-0 left-0 right-0 px-6 py-4 flex justify-between items-center z-10 top-bar">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <h1 class="text-xl font-semibold">{{ auth()->user()?->name ?? 'Nama' }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <button id="roomThemeToggle" class="p-2 hover:bg-white/10 rounded-full transition text-white/70 hover:text-white" title="Toggle tema">
                    <svg id="roomThemeIconSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg id="roomThemeIconMoon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Hidden statuses -->
        <div class="hidden">
            <span id="connectionStatus"></span>
            <span id="pipelineStatus"></span>
            <button id="enableAudioBtn">Audio</button>
        </div>

        <!-- Video Grid Area -->
        <div class="flex-1 w-full h-full p-2 pb-24 relative flex flex-col">
            <div id="videoGridWrapper" class="flex-1 overflow-hidden relative flex flex-row video-grid-container m-2">
                <!-- Screen Share Display -->
                <div id="screenShareContainer" class="hidden flex-1 min-w-0 relative screen-share-container m-2" style="background:#111">
                    <video id="screenShareVideo" autoplay playsinline class="w-full h-full object-contain"></video>
                    <div id="screenShareOverlay" class="absolute top-0 left-0 right-0 bg-gradient-to-b from-black/60 to-transparent p-3 flex items-center gap-2">
                        <span id="screenShareLabel" class="text-white text-sm font-semibold"></span>
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    </div>
                    <button id="pinScreenShareBtn" class="absolute top-3 right-3 pin-btn text-xs px-2 py-1.5 z-30">📌</button>
                    <button id="stopScreenShareBtn" class="absolute top-3 right-14 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg z-30 hidden shadow-lg shadow-red-600/20">Stop Sharing</button>
                </div>
                <div id="videoGridMain" class="min-w-0 relative z-0" style="flex:1">
                    <!-- Local Video -->
                    <div id="localVideoContainer" class="relative bg-black rounded-lg overflow-hidden h-full video-card m-1">
                        <video id="localVideo" autoplay muted playsinline class="w-full h-full object-cover"></video>
                        <div id="localAvatar" class="absolute inset-0 bg-gray-900/80 flex items-center justify-center hidden z-10">
                            <div class="relative">
                                <div id="localAvatarCircle" style="width:112px;height:112px;border-radius:50%;background:#374151;display:flex;align-items:center;justify-content:center;transition:all 0.3s">
                                    <span id="localAvatarText" style="font-size:3rem;color:#fff;font-weight:700;text-transform:uppercase"></span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-2 left-2 rec-badge text-white px-2 py-0.5 rounded text-[10px] font-bold z-20 tracking-wider">REC</div>
                    </div>
                    <!-- Remote Videos Container -->
                    <div id="remoteVideos" class="contents"></div>
                </div>
            </div>
            <!-- Pagination Dots -->
            <div id="paginationDots" class="flex justify-center items-center gap-2 py-2 flex-shrink-0" style="display:none"></div>

            <!-- "Simpan Notulen Rapat?" Confirmation Modal -->
            <div id="confirmNotulenModal" class="absolute inset-0 flex items-center justify-center z-40 hidden backdrop-blur-sm bg-black/40">
                <div class="bg-[#242424] border border-gray-700 shadow-2xl rounded-2xl p-8 flex flex-col items-center">
                    <h2 class="text-2xl font-bold text-white mb-6">Simpan Notulen Rapat?</h2>
                    <div class="flex gap-4">
                        <button id="cancelNotulenBtn" class="bg-black text-white px-8 py-2 font-bold text-lg hover:bg-gray-900 transition">Cancel</button>
                        <button id="simpanNotulenBtn" class="bg-black text-white px-8 py-2 font-bold text-lg hover:bg-gray-900 transition">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Toolbar -->
        <div class="absolute bottom-0 left-0 right-0 bottom-toolbar border-t border-gray-700/50 py-3 px-6 flex justify-between md:justify-center md:gap-16 items-center z-50 shadow-[0_-8px_30px_rgba(0,0,0,0.6)]">
            <!-- Kamera -->
            <button id="cameraBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                <div class="h-12 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                </div>
                <span class="text-sm font-semibold mt-1">Kamera</span>
            </button>
            
            <!-- Audio (Mic) -->
            <button id="muteBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                <div class="h-12 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M19 11h-1.7c0 .74-.16 1.43-.43 2.05l1.23 1.23c.56-.98.9-2.09.9-3.28zm-4.02 3.28c-.91.71-2.04 1.16-3.28 1.29v1.94c1.83-.16 3.49-.91 4.77-2.02l-1.49-1.21zM11 20v-2.11c-1.89-.25-3.59-1.21-4.83-2.58l1.41-1.41c1.02 1.13 2.45 1.83 4.02 1.96V20h-1.6zm-5-9H4.3c0 1.19.34 2.3.9 3.28l1.23-1.23c-.27-.62-.43-1.31-.43-2.05zm7-7c-1.66 0-3 1.34-3 3v5.17l3.63-3.63V7c0-.34-.28-.62-.63-.62zm3 8.35L11.65 14H12c1.66 0 3-1.34 3-3V9.65l2-2v4.7zm1.1-6.1L4.27 1 3 2.27l18.73 18.73L23 19.73 17.1 13.83z"/></svg>
                </div>
                <span class="text-sm font-semibold mt-1">Audio</span>
            </button>
            
            <!-- AI Notulen -->
            <button id="aiNotulenTriggerBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative">
                <div class="h-12 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M7.5 5.6L10 7 8.6 4.5 10 2 7.5 3.4 5 2l1.4 2.5L5 7zm12 9.8L17 14l1.4 2.5L17 19l2.5-1.4L22 19l-1.4-2.5L22 14zM22 2l-2.5 1.4L17 2l1.4 2.5L17 7l2.5-1.4L22 7l-1.4-2.5zm-7.63 5.29c-.39-.39-1.02-.39-1.41 0L1.29 18.96c-.39.39-.39 1.02 0 1.41l2.34 2.34c.39.39 1.02.39 1.41 0L16.7 11.05c.39-.39.39-1.02 0-1.41l-2.33-2.35zm-1.03 5.49l-2.12-2.12 2.44-2.44 2.12 2.12-2.44 2.44z"/></svg>
                </div>
                <span class="text-sm font-semibold mt-1">AI Notulen</span>
                <span id="aiNotulenActiveDot" class="hidden absolute top-0 right-2 w-3 h-3 bg-red-500 rounded-full animate-pulse border-2 border-[#9ea3a8]"></span>
            </button>

            <!-- Share -->
            <div class="relative flex flex-col items-center">
                <button id="shareBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                    <div class="h-12 flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"></path></svg>
                    </div>
                    <span class="text-sm font-semibold mt-1">Share</span>
                </button>
                
                <div id="sharePopup" class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-72 bg-white rounded-lg shadow-xl border border-gray-200 p-4 text-gray-800 z-50 hidden transition-opacity opacity-0">
                    <h4 class="font-medium mb-2 text-sm text-left">Bagikan info rapat ini</h4>
                    <p class="text-xs text-gray-500 mb-3 text-left">Berikan link atau ID rapat ini kepada peserta lain.</p>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $meeting->id }}" class="flex-1 bg-gray-50 border border-gray-300 rounded px-2 py-1.5 text-xs text-gray-800 font-mono outline-none">
                            <span class="text-xs text-gray-500 w-12">ID</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ route('meeting.room', $meeting->id) }}" class="flex-1 bg-gray-50 border border-gray-300 rounded px-2 py-1.5 text-xs text-gray-600 outline-none">
                            <button onclick="navigator.clipboard.writeText('{{ route('meeting.room', $meeting->id) }}'); alert('Link disalin ke clipboard!')" class="p-1.5 bg-blue-50 text-[#0284c7] hover:bg-blue-100 rounded transition shrink-0" title="Salin link">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Partisipan -->
            <button id="participantBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative">
                <div class="h-12 flex items-center justify-center relative">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    <span id="participantBadge" class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">1</span>
                </div>
                <span class="text-sm font-semibold mt-1">Partisipan</span>
            </button>

            <!-- Share Screen -->
            <button id="screenShareBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative">
                <div class="h-12 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"></svg>
                </div>
                <span class="text-sm font-semibold mt-1">Share Screen</span>
                <span id="screenShareActiveDot" class="hidden absolute -top-0.5 right-1 w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
            </button>

            <!-- Layout Selector -->
            <div class="relative flex flex-col items-center">
                <button id="layoutBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                    <div class="h-12 flex items-center justify-center">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>
                    </div>
                    <span class="text-sm font-semibold mt-1">Layout</span>
                </button>
                <div id="layoutDropdown" class="hidden absolute bottom-full mb-4 left-1/2 -translate-x-1/2 layout-dropdown min-w-[160px] opacity-0 transition-opacity">
                    <button data-layout="grid" class="active-layout"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg> Grid</button>
                    <button data-layout="speaker"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg> Speaker</button>
                    <button data-layout="sidebar"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3v18h18V3H3zm8 16H5V5h6v14zm8 0h-6V5h6v14z"/></svg> Sidebar</button>
                    <button data-layout="spotlight"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z"/></svg> Spotlight</button>
                </div>
            </div>

            <!-- Rekam Layar -->
            <button id="recordScreenBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative">
                <div class="h-12 flex items-center justify-center">
                    <svg id="recordIconDefault" class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z"/></svg>
                    <svg id="recordIconActive" class="hidden w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/><rect x="6" y="9" width="8" height="6" rx="1" fill="#ef4444"/></svg>
                </div>
                <span class="text-sm font-semibold mt-1">Rekam</span>
                <span id="recordActiveDot" class="hidden absolute -top-0.5 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
            </button>
            
            <!-- Akhiri -->
            <button id="leaveBtn" class="flex flex-col items-center transition toolbar-btn ml-8">
                <div class="h-12 flex items-center justify-center">
                    <div class="btn-danger text-white font-bold rounded-xl px-5 py-2.5 shadow-lg tracking-wide">
                        Akhiri
                    </div>
                </div>
            </button>
        </div>

        <!-- Participant Sidebar -->
        <div id="participantSidebar" class="absolute top-0 right-0 h-full w-80 participant-sidebar z-40 transform translate-x-full transition-transform duration-300 flex flex-col">
            <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                <h2 class="text-lg font-bold text-white flex items-center gap-2"><svg class="w-5 h-5 text-violet-400" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg> Partisipan <span class="text-violet-400 font-bold">(<span id="participantCountText">1</span>)</span></h2>
                <button id="closeParticipantBtn" class="text-gray-500 hover:text-white transition hover:bg-white/5 rounded-lg p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="participantList" class="flex-1 p-4 overflow-y-auto flex flex-col gap-3 custom-scrollbar text-gray-300">
            </div>
            <div id="contextMenu" class="hidden fixed z-50 context-menu py-1 min-w-[140px]">
                <button id="contextPinBtn" class="w-full text-left px-4 py-2.5 text-sm text-gray-200 hover:text-white flex items-center gap-2">📌 Pin</button>
            </div>
        </div>

        <div id="transcriptSidebar" class="hidden"></div>
        <button id="startRecordBtn" class="hidden"></button>
        <button id="stopUploadBtn" class="hidden"></button>
        <a id="showNotulensiBtn" class="hidden"></a>
        <a id="pdfBtn" class="hidden"></a>
        <div id="transcriptMessages" class="hidden"></div>

        <!-- Hidden canvas for screen recording -->
        <canvas id="recordingCanvas" class="hidden" width="1920" height="1080"></canvas>

        <!-- Layout containers -->
        <div id="speakerMainVideo" class="hidden"></div>
        <div id="speakerStrip" class="hidden"></div>
        <div id="sidebarMainArea" class="hidden"></div>
        <div id="sidebarStrip" class="hidden"></div>
    </div>

    <!-- Modal Notulensi -->
    <div id="notulensiModal"
        class="fixed inset-0 bg-black/75 backdrop-blur-md flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
        <div
            class="bg-gray-900/90 border border-gray-700/80 rounded-2xl w-11/12 max-w-4xl h-[85vh] flex flex-col shadow-2xl scale-95 transition-transform duration-300 overflow-hidden">
            <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-gray-950/40">
                <div class="flex items-center gap-3"><span class="flex h-3 w-3 relative"><span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span><span
                            class="relative inline-flex rounded-full h-3 w-3 bg-violet-500"></span></span>
                    <h2 class="text-lg font-bold text-white">Notulensi Rapat AI (Gemini)</h2>
                </div>
                <button id="closeNotulensiModalBtn"
                    class="text-gray-400 hover:text-white transition text-sm font-semibold bg-gray-800 hover:bg-gray-700 px-3.5 py-1.5 rounded-lg">Tutup</button>
            </div>
            <div class="flex-1 p-6 overflow-y-auto space-y-5 custom-scrollbar">
                <div id="modalNotulensiContent" class="space-y-5">
                    <div class="rounded-xl border p-5" style="background:rgba(139,92,246,0.04);border-color:rgba(139,92,246,0.18)">
                        <div class="flex items-center gap-2.5 mb-3">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            <h3 class="text-sm font-bold text-violet-400">Ringkasan Eksekutif</h3>
                            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(139,92,246,0.12);color:#a78bfa">Gemini AI</span>
                        </div>
                        <p id="modalRingkasan" class="text-sm leading-relaxed whitespace-pre-line text-gray-300">Memproses...</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-xl p-5" style="background:rgba(16,185,129,0.03);border:1px solid rgba(16,185,129,0.15)">
                            <div class="flex items-center gap-2.5 mb-3">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <h3 class="text-sm font-bold text-emerald-400">Topik Dibahas</h3>
                            </div>
                            <ul id="modalTopik" class="space-y-2"></ul>
                        </div>
                        <div class="rounded-xl p-5" style="background:rgba(251,191,36,0.03);border:1px solid rgba(251,191,36,0.15)">
                            <div class="flex items-center gap-2.5 mb-3">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h3 class="text-sm font-bold text-amber-400">Keputusan Penting</h3>
                            </div>
                            <ul id="modalKeputusan" class="space-y-2"></ul>
                        </div>
                    </div>
                    <div class="rounded-xl p-5" style="background:rgba(56,189,248,0.03);border:1px solid rgba(56,189,248,0.15)">
                        <div class="flex items-center gap-2.5 mb-3">
                            <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <h3 class="text-sm font-bold text-sky-400">Action Items</h3>
                        </div>
                        <div class="overflow-x-auto rounded-lg border" style="border-color:rgba(56,189,248,0.12)">
                            <table class="w-full text-left text-sm">
                                <thead style="background:rgba(56,189,248,0.05)">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">Tugas</th>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">PIC</th>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">Deadline</th>
                                    </tr>
                                </thead>
                                <tbody id="modalActionItems" class="divide-y text-gray-300" style="border-color:rgba(56,189,248,0.06)"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="rounded-xl p-5" style="background:rgba(244,63,94,0.03);border:1px solid rgba(244,63,94,0.15)">
                        <div class="flex items-center gap-2.5 mb-3">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <h3 class="text-sm font-bold text-rose-400">Risiko / Catatan</h3>
                        </div>
                        <ul id="modalRisiko" class="space-y-2"></ul>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-800 bg-gray-950/40 flex justify-end gap-3">
                <a id="modalPdfBtn" href="#" target="_blank"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition">Unduh PDF</a>
                <button id="closeNotulensiModalFooterBtn"
                    class="bg-gray-800 hover:bg-gray-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition">Tutup</button>
            </div>
        </div>
    </div>

    <div id="aiLoadingOverlay"
        class="fixed inset-0 bg-black/85 backdrop-blur-sm flex flex-col items-center justify-center z-[60] hidden opacity-0 transition-opacity duration-300">
        <div class="flex flex-col items-center space-y-6 text-center max-w-md p-6">
            <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-violet-500"></div>
                <div class="absolute animate-ping rounded-full h-12 w-12 bg-violet-400/20"></div>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">AI Sedang Menyusun Notulensi</h3>
                <p class="text-sm text-gray-400">Gemini sedang menganalisis transkrip percakapan...</p>
            </div>
            <div class="w-full bg-gray-800 h-1.5 rounded-full overflow-hidden">
                <div class="bg-violet-500 h-full absolute top-0 left-0 animate-[loading-bar_3s_infinite_linear]"
                    style="width:50%"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes loading-bar {
            0% { left: -50% }
            100% { left: 100% }
        }
    </style>

    <script>
        // ======================== DEKLARASI VARIABEL ========================
        let room = null;
        let localStream = null;
        let isMuted = false, isCameraOff = false, audioEnabledByUser = false;
        let isScreenSharing = false;
        let screenShareStream = null;
        let pinnedIdentities = [];
        let currentLayout = localStorage.getItem('layout_' + @json($meeting->id)) || 'grid';
        let isRecordingScreen = false;
        let recordingMediaRecorder = null;
        let recordingChunks = [];
        let recordingCanvasCtx = null;
        let recordingRenderTimer = null;
        let recordingAudioMixer = null;
        let recordingAudioSource = null;
        let recordingAudioDestination = null;
        let recordingCanvas = null;
        let recordingBgCanvas = null;
        let recordingThumbCanvas = null;
        let recordingParticipants = [];
        let recordingSpeakerQueue = [];
        let recordingVideoCache = new Map();
        let thumbnailsDirty = true;
        let bgDirty = true;
        let recordingActiveSpeakers = [];
        let activeSpeakerIdentity = null;
        let spotlightTargetIdentity = null;
        const meetingId = @json($meeting->id);
        // Load saved device state
        try { const s = JSON.parse(localStorage.getItem('device_' + meetingId)); if (s) { isMuted = !!s.m; isCameraOff = !!s.c; } } catch (e) {}
        const currentUserId = Number(@json(auth()->id()));
        const authName = @json(auth()->user()?->name ?? 'Anda');
        const baseUrl = '/meeting/' + meetingId;
        const liveKitUrl = @json($liveKitUrl);
        const livekitTokenUrl = baseUrl + '/livekit-token';
        const broadcastUrl = baseUrl + '/broadcast';
        const leaveUrl = baseUrl + '/leave';
        const saveLiveTranscriptUrl = baseUrl + '/save-live-transcript';
        const notulensiPdfUrl = baseUrl + '/notulensi-pdf';
        const whisperWsUrl = 'ws://127.0.0.1:8001/ws/transcribe';

        // Dynamic Reverb config for LAN access (overrides VITE_* env if accessed via IP)
        const wsHost = window.location.hostname;
        window._REVERB_CONFIG = {
            host: wsHost,
            wsPort: 8080,
            wssPort: 8080,
            scheme: 'http',
            key: '{{ env("REVERB_APP_KEY") }}',
            authEndpoint: '/broadcasting/auth',
        };

        // DOM references
        const localVideo = document.getElementById('localVideo');
        const remoteVideos = document.getElementById('remoteVideos');
        const muteBtn = document.getElementById('muteBtn');
        const cameraBtn = document.getElementById('cameraBtn');
        const leaveBtn = document.getElementById('leaveBtn');
        const startRecordBtn = document.getElementById('startRecordBtn');
        const stopUploadBtn = document.getElementById('stopUploadBtn');
        const recordScreenBtn = document.getElementById('recordScreenBtn');
        const recordActiveDot = document.getElementById('recordActiveDot');
        const recordIconDefault = document.getElementById('recordIconDefault');
        const recordIconActive = document.getElementById('recordIconActive');
        const layoutBtn = document.getElementById('layoutBtn');
        const layoutDropdown = document.getElementById('layoutDropdown');
        const pdfBtn = document.getElementById('pdfBtn');
        const connectionStatusEl = document.getElementById('connectionStatus');
        const enableAudioBtn = document.getElementById('enableAudioBtn');
        const localAvatar = document.getElementById('localAvatar');
        const localAvatarText = document.getElementById('localAvatarText');
        const transcriptSidebar = document.getElementById('transcriptSidebar');
        const transcriptMessages = document.getElementById('transcriptMessages');
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const openSidebarBtn = document.getElementById('openSidebarBtn');

        // ======================== DEVICE STATE PERSISTENCE ========================
        function saveDeviceState() {
            try { localStorage.setItem('device_' + meetingId, JSON.stringify({ m: isMuted, c: isCameraOff })); } catch (e) {}
        }
        function applyDeviceState() {
            if (muteBtn) {
                muteBtn.classList.toggle('text-red-400', isMuted);
                muteBtn.classList.toggle('text-white', !isMuted);
            }
            if (cameraBtn) {
                cameraBtn.classList.toggle('text-red-400', isCameraOff);
                cameraBtn.classList.toggle('text-white', !isCameraOff);
            }
            if (localAvatar) localAvatar.classList.toggle('hidden', !isCameraOff);
            if (localAvatarText && isCameraOff) localAvatarText.textContent = authName.charAt(0).toUpperCase();
        }
        applyDeviceState();
        const localAvatarCircle = document.getElementById('localAvatarCircle');
        let audioMonitorInterval = null;
        function startAudioMonitor() {
            stopAudioMonitor();
            audioMonitorInterval = setInterval(() => {
                const level = room?.localParticipant?.audioLevel || 0;
                if (!localAvatarCircle) return;
                if (level > 0.02) {
                    localAvatarCircle.classList.add('speaking-ring');
                    const localId = 'local_' + currentUserId;
                    recordingSpeakerQueue = [localId, ...recordingSpeakerQueue.filter(id => id !== localId)].slice(0, 20);
                    if (recordingVideoCache.has(localId)) recordingVideoCache.get(localId).isSpeaking = true;
                } else {
                    localAvatarCircle.classList.remove('speaking-ring');
                    const localId = 'local_' + currentUserId;
                    if (recordingVideoCache.has(localId)) recordingVideoCache.get(localId).isSpeaking = false;
                }
            }, 200);
        }
        function stopAudioMonitor() {
            if (audioMonitorInterval) {
                clearInterval(audioMonitorInterval);
                audioMonitorInterval = null;
            }
            if (localAvatarCircle) localAvatarCircle.classList.remove('speaking-ring');
        }
        const clearTranscriptBtn = document.getElementById('clearTranscriptBtn');
        const sidebarPulse = document.getElementById('sidebarPulse');
        const sidebarStatusIndicator = document.getElementById('sidebarStatusIndicator');
        const transcribeStatusEl = document.getElementById('transcribeStatus');
        const showNotulensiBtn = document.getElementById('showNotulensiBtn');
        const notulensiModal = document.getElementById('notulensiModal');

        // Live transcription vars
        let liveTranscriptionActive = false;
        let isWhisperSocketOpen = false;
        let whisperSocket = null;
        let whisperRequestQueue = []; // [{userId, name}] - tracks which speaker's PCM is being processed
        let lastSpeakerId = null;
        let lastMessageElement = null;

        const participantTranscribers = new Map();
        // key: identity (string)
        // value: { audioContext, processor, source, pcmBuffer,
        //          silenceFrames, isSpeaking, wasSpeaking, userId, name }

        function createTrackTranscriber(identity, userId, name) {
            if (participantTranscribers.has(identity)) return participantTranscribers.get(identity);
            const state = {
                audioContext: null,
                source: null,
                processor: null,
                pcmBuffer: [],
                silenceFrames: 0,
                isSpeaking: false,
                wasSpeaking: false,
                userId: userId,
                name: name,
                identity: identity
            };
            participantTranscribers.set(identity, state);
            return state;
        }

        function removeTrackTranscriber(identity) {
            const state = participantTranscribers.get(identity);
            if (!state) return;
            if (state.processor) try { state.processor.disconnect(); } catch (e) {}
            if (state.source) try { state.source.disconnect(); } catch (e) {}
            if (state.audioContext) try { state.audioContext.close(); } catch (e) {}
            participantTranscribers.delete(identity);
            if (lastSpeakerId === state.userId) { lastSpeakerId = null; lastMessageElement = null; }
        }

        function removeAllTranscribers() {
            for (const identity of participantTranscribers.keys()) {
                removeTrackTranscriber(identity);
            }
        }

        // Remote participant metadata
        const remoteParticipants = new Map();

        // ======================== FUNGSI UTILITY ========================
        function setConnectionStatus(text, colorClass) {
            if (connectionStatusEl) {
                connectionStatusEl.textContent = text;
                connectionStatusEl.className = 'text-xs ' + (colorClass || 'text-amber-300');
            }
        }

        function updateSidebarStatus(statusText, textColorClass, pulseColorClass) {
            if (transcribeStatusEl) {
                transcribeStatusEl.textContent = statusText;
                transcribeStatusEl.className = `${textColorClass} font-semibold uppercase tracking-wider`;
            }
            if (sidebarStatusIndicator) sidebarStatusIndicator.className =
                `relative inline-flex rounded-full h-2 w-2 ${pulseColorClass}`;
            if (sidebarPulse) sidebarPulse.className =
                `animate-ping absolute inline-flex h-full w-full rounded-full ${pulseColorClass} opacity-75`;
        }

        function escapeHtml(str) {
            return str?.replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;') || '';
        }
        function escapeHtmlAttr(str) {
            return (str ?? '').replace(/[&<>"']/g, m => {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                if (m === '"') return '&quot;';
                if (m === "'") return '&#39;';
                return m;
            });
        }

        function appendTranscriptMessage(userId, name, text) {
            if (!transcriptMessages) return;
            const emptyMsg = document.getElementById('emptyTranscriptMsg');
            if (emptyMsg) emptyMsg.remove();

            if (userId === lastSpeakerId && lastMessageElement) {
                const textSpan = lastMessageElement.querySelector('.transcript-text');
                if (textSpan) {
                    textSpan.textContent += ' ' + text;
                    transcriptMessages.scrollTop = transcriptMessages.scrollHeight;
                    return;
                }
            }

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const isMe = Number(userId) === currentUserId;
            const nameColors = ['text-indigo-400', 'text-emerald-400', 'text-amber-400', 'text-pink-400', 'text-sky-400', 'text-purple-400'];
            const colorIndex = Number(userId) % nameColors.length;
            const nameStyle = isMe ? 'text-violet-400 font-bold' : nameColors[colorIndex] + ' font-semibold';
            const div = document.createElement('div');
            div.className = 'hover:bg-gray-800/35 px-2.5 py-1.5 rounded-lg transition-colors text-xs';
            div.innerHTML = `<span class="text-[9px] text-gray-500 font-mono mr-1.5">[${time}]</span><strong class="${nameStyle} mr-1.5">${escapeHtml(name)}:</strong><span class="transcript-text text-gray-300">${escapeHtml(text)}</span>`;
            transcriptMessages.appendChild(div);
            transcriptMessages.scrollTop = transcriptMessages.scrollHeight;
            lastSpeakerId = userId;
            lastMessageElement = div;
        }

        async function syncTranscriptToLaravel(text, speakerId, speakerName) {
            try {
                await fetch(saveLiveTranscriptUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ text, speaker_id: speakerId, speaker_name: speakerName })
                });
            } catch (e) {
                console.error(e);
            }
        }

        function sendBroadcast(data) {
            fetch(broadcastUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
        }

        async function leaveMeeting() {
            stopAudioMonitor();
            if (screenShareStream) { screenShareStream.getTracks().forEach(t => t.stop()); screenShareStream = null; }
            isScreenSharing = false;
            if (room) {
                await room.disconnect();
                room = null;
            }
            fetch(leaveUrl, {
                method: 'POST',
                keepalive: true,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).finally(() => {
                window.location.href = '/join';
            });
        }

        // ======================== LIVEKIT SFU ========================
        async function fetchLiveKitToken() {
            const res = await fetch(livekitTokenUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Gagal mendapatkan token LiveKit');
            return res.json();
        }

        function createRemoteVideoCard(identity, displayName) {
            const safeKey = identity.replace(/[^a-zA-Z0-9_-]/g, '_');
            const cardId = `remote-card-${safeKey}`;
            const videoId = `remote-video-${safeKey}`;
            let card = document.getElementById(cardId);
            if (card) {
                return { card, video: document.getElementById(videoId), safeKey };
            }
            card = document.createElement('div');
            card.id = cardId;
            card.dataset.identity = identity;
            card.className = 'bg-black rounded-2xl overflow-hidden shadow-xl h-full w-full relative video-card m-1';
            const video = document.createElement('video');
            video.id = videoId;
            video.autoplay = true;
            video.playsInline = true;
            video.className = 'w-full h-full object-cover';
            video.muted = true;
            const label = document.createElement('div');
            label.className = 'absolute bottom-2 left-2 text-xs px-2 py-1 rounded name-label text-gray-200';
            label.textContent = displayName || identity;
            const pinBtn = document.createElement('button');
            pinBtn.id = `pin-btn-${safeKey}`;
            pinBtn.dataset.identity = identity;
            pinBtn.className = 'absolute top-2 right-2 pin-btn text-xs px-1.5 py-0.5 z-20 transition-colors';
            pinBtn.textContent = '📌';
            pinBtn.onclick = (e) => { e.stopPropagation(); togglePin(identity); };
            const avatar = document.createElement('div');
            avatar.id = `remote-avatar-${safeKey}`;
            avatar.className = 'absolute inset-0 bg-gray-900/80 flex items-center justify-center hidden z-10';
            avatar.innerHTML = `<div class="relative"><div id="remote-avatar-circle-${safeKey}" style="width:112px;height:112px;border-radius:50%;background:#374151;display:flex;align-items:center;justify-content:center;transition:all 0.3s"><span style="font-size:3rem;color:#fff;font-weight:700;text-transform:uppercase">${(displayName || identity || 'P').charAt(0).toUpperCase()}</span></div></div>`;
            card.appendChild(video);
            card.appendChild(pinBtn);
            card.appendChild(avatar);
            card.appendChild(label);
            remoteVideos.appendChild(card);
            remoteParticipants.set(identity, { identity, displayName, cardId, videoId });
            scheduleParticipantUIUpdate();
            return { card, video, safeKey };
        }

        function removeRemoteVideoCard(identity) {
            const safeKey = (identity || '').replace(/[^a-zA-Z0-9_-]/g, '_');
            const card = document.getElementById(`remote-card-${safeKey}`);
            if (card) card.remove();
            remoteParticipants.delete(identity);
            scheduleParticipantUIUpdate();
        }

        let currentPage = 0;
        const PER_PAGE = 4;

        function getParticipantCards() {
            const remoteContainer = document.getElementById('remoteVideos');
            const remotes = remoteContainer ? Array.from(remoteContainer.querySelectorAll(':scope > [id^="remote-card-"]')) : [];
            const localEl = document.getElementById('localVideoContainer');
            const all = [];
            if (localEl) all.push(localEl);
            remotes.forEach(el => all.push(el));
            return all;
        }

        let _pendingUIUpdate = false;
        function scheduleParticipantUIUpdate() {
            if (_pendingUIUpdate) return;
            _pendingUIUpdate = true;
            requestAnimationFrame(() => {
                _pendingUIUpdate = false;
                updateParticipantUI();
            });
        }

        function applyLayout(mode) {
            currentLayout = mode;
            localStorage.setItem('layout_' + meetingId, mode);
            updateParticipantUI();
            // Update dropdown active state
            document.querySelectorAll('#layoutDropdown button').forEach(btn => {
                btn.classList.toggle('active-layout', btn.dataset.layout === mode);
            });
        }

        function getVideoCardByIdentity(identity) {
            const safeKey = String(identity).replace(/[^a-zA-Z0-9_-]/g, '_');
            if (String(identity) === String(currentUserId)) return document.getElementById('localVideoContainer');
            return document.getElementById('remote-card-' + safeKey);
        }

        let _lastUIUpdateKey = '';
        function updateParticipantUI() {
            const grid = document.getElementById('videoGridMain');
            const remoteContainer = document.getElementById('remoteVideos');
            if (!grid || !remoteContainer) return;
            const remotes = Array.from(remoteContainer.querySelectorAll(':scope > [id^="remote-card-"]'));
            const totalCount = 1 + remotes.length;
            const key = `${currentLayout}|${totalCount}|${remotes.map(el => el.dataset.identity).join(',')}`;
            if (_lastUIUpdateKey === key) return;
            _lastUIUpdateKey = key;

            // Remove all layout classes
            grid.classList.remove('layout-speaker', 'layout-sidebar', 'layout-spotlight');
            grid.className = 'min-w-0 relative z-0';

            if (currentLayout === 'speaker') {
                applySpeakerLayout(grid, remotes, totalCount);
            } else if (currentLayout === 'sidebar') {
                applySidebarLayout(grid, remotes, totalCount);
            } else if (currentLayout === 'spotlight') {
                applySpotlightLayout(grid, remotes, totalCount);
            } else {
                applyGridLayout(grid, remotes, totalCount);
            }

            const badge = document.getElementById('participantBadge');
            const countText = document.getElementById('participantCountText');
            if (badge) badge.textContent = totalCount;
            if (countText) countText.textContent = totalCount;
            updateParticipantSidebar();
        }

        function applyGridLayout(grid, remotes, totalCount) {
            grid.classList.add('grid', 'gap-2', 'w-full', 'h-full');
            const cards = getParticipantCards();
            if (totalCount === 1) {
                grid.classList.add('grid-cols-1', 'grid-rows-1');
                cards.forEach(el => { el.style.display = ''; el.classList.remove('speaker-main-video'); });
            } else if (totalCount === 2) {
                grid.classList.add('grid-cols-2', 'grid-rows-1');
                cards.forEach(el => { el.style.display = ''; el.classList.remove('speaker-main-video'); });
            } else {
                const cols = totalCount <= 4 ? 2 : 3;
                const rows = Math.ceil(totalCount / cols);
                grid.classList.add(`grid-cols-${cols}`);
                if (rows === 1) grid.classList.add('grid-rows-1');
                else if (rows === 2) grid.classList.add('grid-rows-2');
                else grid.classList.add('grid-rows-3');
                cards.forEach(el => { el.style.display = ''; el.classList.remove('speaker-main-video'); });
            }
        }

        function applySpeakerLayout(grid, remotes, totalCount) {
            grid.classList.add('layout-speaker');
            const cards = getParticipantCards();
            const mainId = activeSpeakerIdentity || pinnedIdentities[0] || (remotes.length > 0 ? remotes[0].dataset.identity : null);

            // Create/maintain speaker strip container
            let strip = grid.querySelector('.speaker-strip');
            if (!strip) {
                strip = document.createElement('div');
                strip.className = 'speaker-strip';
                grid.appendChild(strip);
            }
            strip.innerHTML = '';

            cards.forEach(card => {
                card.classList.remove('speaker-main-video');
                const id = card.dataset?.identity || String(currentUserId);
                if (id === mainId || (mainId === null && card.id === 'localVideoContainer')) {
                    card.classList.add('speaker-main-video');
                    card.style.display = '';
                    if (card.parentElement !== grid) grid.insertBefore(card, strip);
                } else {
                    card.style.display = '';
                    if (card.parentElement !== strip) strip.appendChild(card);
                }
            });
        }

        function applySidebarLayout(grid, remotes, totalCount) {
            grid.classList.add('layout-sidebar');
            let mainArea = grid.querySelector('.sidebar-main-area');
            let vstrip = grid.querySelector('.sidebar-vertical-strip');
            if (!mainArea) {
                mainArea = document.createElement('div');
                mainArea.className = 'sidebar-main-area';
                grid.appendChild(mainArea);
            }
            if (!vstrip) {
                vstrip = document.createElement('div');
                vstrip.className = 'sidebar-vertical-strip';
                grid.appendChild(vstrip);
            }

            const cards = getParticipantCards();
            const mainId = activeSpeakerIdentity || pinnedIdentities[0] || (remotes.length > 0 ? remotes[0].dataset.identity : null);

            cards.forEach(card => {
                const id = card.dataset?.identity || String(currentUserId);
                if (id === mainId || (mainId === null && card.id === 'localVideoContainer')) {
                    card.style.display = '';
                    if (card.parentElement !== mainArea) mainArea.appendChild(card);
                } else {
                    card.style.display = '';
                    if (card.parentElement !== vstrip) vstrip.appendChild(card);
                }
            });
        }

        function applySpotlightLayout(grid, remotes, totalCount) {
            grid.classList.add('layout-spotlight');
            const cards = getParticipantCards();
            const target = spotlightTargetIdentity || activeSpeakerIdentity || pinnedIdentities[0] || (remotes.length > 0 ? remotes[0].dataset.identity : null);

            // Remove old overlay classes
            cards.forEach(c => c.classList.remove('spotlight-main', 'spotlight-overlay'));

            let overlayIndex = 0;
            cards.forEach(card => {
                card.style.display = '';
                const id = card.dataset?.identity || String(currentUserId);
                if (id === target || (target === null && card.id === 'localVideoContainer')) {
                    card.classList.add('spotlight-main');
                } else {
                    card.classList.add('spotlight-overlay');
                    const overlayPositions = [
                        { bottom: 16, right: 16 },
                        { bottom: 16, right: 210 },
                        { bottom: 16, right: 404 },
                        { bottom: 16, right: 598 },
                        { bottom: 16, left: 16 }
                    ];
                    const pos = overlayPositions[overlayIndex] || { bottom: 16, right: 16 };
                    card.style.bottom = pos.bottom + 'px';
                    card.style.right = pos.right + 'px';
                    overlayIndex++;
                }
            });
        }

        let _sidebarCacheKey = '';
        function updateParticipantSidebar() {
            const list = document.getElementById('participantList');
            const remoteContainer = document.getElementById('remoteVideos');
            if (!list || !remoteContainer) return;
            const remotes = Array.from(remoteContainer.querySelectorAll(':scope > [id^="remote-card-"]'));
            const keys = remotes.map(el => el.dataset.identity).join(',');
            const cacheKey = `${currentUserId}|${remotes.length}|${keys}`;
            if (_sidebarCacheKey === cacheKey) return;
            _sidebarCacheKey = cacheKey;
            const totalCount = 1 + remotes.length;
            let html = `
                <div data-identity="${escapeHtmlAttr(String(currentUserId))}" class="flex items-center justify-between p-3 bg-gray-800 rounded-xl cursor-pointer hover:bg-gray-700 transition-colors sidebar-item">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-sm">
                            ${escapeHtml(authName.charAt(0).toUpperCase())}
                        </div>
                        <span class="font-semibold text-white">${escapeHtml(authName)} (Anda)</span>
                    </div>
                </div>
            `;
            remoteParticipants.forEach((meta) => {
                const name = meta.displayName || meta.identity || 'Participant';
                html += `
                    <div data-identity="${escapeHtmlAttr(meta.identity)}" class="flex items-center justify-between p-3 bg-gray-800 rounded-xl cursor-pointer hover:bg-gray-700 transition-colors sidebar-item">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-sm">
                                ${escapeHtml(name.charAt(0).toUpperCase())}
                            </div>
                            <span class="font-semibold text-gray-300">${escapeHtml(name)}</span>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        async function waitForLiveKit(timeout = 10000) {
            const start = Date.now();
            while (typeof LiveKit === 'undefined') {
                if (Date.now() - start > timeout) {
                    throw new Error('LiveKit library gagal dimuat.');
                }
                await new Promise(r => setTimeout(r, 50));
            }
        }

        async function connectToLiveKit() {
            try {
                setConnectionStatus('LiveKit: menghubungkan...', 'text-amber-300');
                if (!navigator.mediaDevices?.getUserMedia) {
                    alert('Akses kamera/mikrofon tidak tersedia. Buka halaman ini via HTTPS atau localhost.');
                    setConnectionStatus('Media tidak tersedia', 'text-red-400');
                    return;
                }
                await waitForLiveKit();
                const { token, serverUrl } = await fetchLiveKitToken();
                room = new LiveKit.Room({
                    adaptiveStream: true,
                    dynacast: true,
                    videoCaptureDefaults: { resolution: LiveKit.VideoPresets.h720 },
                });
                room.on(LiveKit.RoomEvent.TrackSubscribed, (track, publication, participant) => {
                    if (publication.source === LiveKit.Track.Source.ScreenShare) {
                        if (participant.isLocal) return;
                        showRemoteScreenShare(track, participant);
                        return;
                    }
                    if (publication.source === LiveKit.Track.Source.Microphone && !participant.isLocal && liveTranscriptionActive && !participantTranscribers.has(participant.identity)) {
                        const tracks = [];
                        if (track.mediaStream) {
                            track.mediaStream.getAudioTracks().forEach(t => tracks.push(t));
                        }
                        if (tracks.length === 0 && track.mediaStreamTrack) {
                            tracks.push(track.mediaStreamTrack);
                        }
                        if (tracks.length > 0) {
                            const userId = Number(participant.identity) || participant.identity;
                            const displayName = participant.name || participant.identity;
                            const state = createTrackTranscriber(participant.identity, userId, displayName);
                            startTrackAudioCapture(state, tracks);
                        }
                        return;
                    }
                    if (participant.isLocal) return;
                    const identity = participant.identity;
                    const displayName = participant.name || identity;
                    const { video } = createRemoteVideoCard(identity, displayName);
                    track.attach(video);
                    recordingVideoCache.set(identity, { videoEl: video, name: displayName, identity, isSpeaking: false });
                    updateRecordingParticipants();
                });
                room.on(LiveKit.RoomEvent.TrackUnsubscribed, (track, publication, participant) => {
                    if (publication?.source === LiveKit.Track.Source.ScreenShare) {
                        hideScreenShare();
                        bgDirty = true;
                        return;
                    }
                    if (publication?.source === LiveKit.Track.Source.Camera) {
                        scheduleParticipantUIUpdate();
                    }
                    track.detach();
                    recordingVideoCache.delete(participant.identity);
                    updateRecordingParticipants();
                });
                room.on(LiveKit.RoomEvent.ParticipantDisconnected, (participant) => {
                    pinnedIdentities = pinnedIdentities.filter(id => id !== participant.identity);
                    updatePinIndicators();
                    removeRemoteVideoCard(participant.identity);
                    if (liveTranscriptionActive) {
                        removeTrackTranscriber(participant.identity);
                    }
                    recordingVideoCache.delete(participant.identity);
                    updateRecordingParticipants();
                });
                room.on(LiveKit.RoomEvent.ConnectionStateChanged, (state) => {
                    if (state === LiveKit.ConnectionState.Connected) {
                        setConnectionStatus('LiveKit: terhubung', 'text-emerald-400');
                    } else if (state === LiveKit.ConnectionState.Disconnected) {
                        setConnectionStatus('LiveKit: terputus', 'text-amber-300');
                    } else if (state === LiveKit.ConnectionState.Reconnecting) {
                        setConnectionStatus('LiveKit: reconnect...', 'text-amber-300');
                    }
                });
                room.on(LiveKit.RoomEvent.ActiveSpeakersChanged, (speakers) => {
                    document.querySelectorAll('[id^="remote-avatar-circle-"].speaking-ring').forEach(el => el.classList.remove('speaking-ring'));
                    recordingActiveSpeakers = speakers;
                    const newActive = speakers.find(p => !p.isLocal);
                    activeSpeakerIdentity = newActive ? newActive.identity : null;
                    speakers.forEach(p => {
                        if (p.isLocal) return;
                        const safeKey = (p.identity || '').replace(/[^a-zA-Z0-9_-]/g, '_');
                        const circle = document.getElementById('remote-avatar-circle-' + safeKey);
                        if (circle) circle.classList.add('speaking-ring');
                        recordingSpeakerQueue = [p.identity, ...recordingSpeakerQueue.filter(id => id !== p.identity)].slice(0, 20);
                    });
                    if (currentLayout === 'speaker' || currentLayout === 'sidebar' || currentLayout === 'spotlight') {
                        scheduleParticipantUIUpdate();
                    }
                    updateRecordingParticipants();
                });
                await room.connect(serverUrl, token);
                subscribeEchoChannel();
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: { echoCancellation: true, noiseSuppression: true, autoGainControl: true }
                });
                localVideo.srcObject = localStream;
                const videoTrack = localStream.getVideoTracks()[0];
                const audioTrack = localStream.getAudioTracks()[0];
                // Disable tracks FIRST if saved state says muted/camera off (avoid brief flash)
                if (isCameraOff && videoTrack) videoTrack.enabled = false;
                if (isMuted && audioTrack) audioTrack.enabled = false;
                if (videoTrack) {
                    await room.localParticipant.publishTrack(videoTrack, {
                        name: 'camera',
                        source: LiveKit.Track.Source.Camera,
                    });
                    if (isCameraOff) {
                        const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Camera);
                        if (pub?.track) pub.track.mute().catch(e => console.warn(e));
                    }
                }
                if (audioTrack) {
                    await room.localParticipant.publishTrack(audioTrack, {
                        name: 'microphone',
                        source: LiveKit.Track.Source.Microphone,
                    });
                    if (isMuted) {
                        const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Microphone);
                        if (pub?.track) pub.track.mute().catch(e => console.warn(e));
                    }
                }
                setConnectionStatus('LiveKit: terhubung', 'text-emerald-400');
                updateParticipantUI();
                if (isCameraOff) startAudioMonitor();
                if (@json($meeting->pipeline_status ?? 'idle') === 'processing') startPipelinePolling();
            } catch (error) {
                console.error(error);
                setConnectionStatus('LiveKit: gagal', 'text-red-400');
                alert('Gagal terhubung ke server meeting.');
            }
        }

        // ======================== SCREEN SHARE ========================
        const screenShareBtn = document.getElementById('screenShareBtn');
        const screenShareContainer = document.getElementById('screenShareContainer');
        const screenShareVideo = document.getElementById('screenShareVideo');
        const screenShareLabel = document.getElementById('screenShareLabel');
        const stopScreenShareBtn = document.getElementById('stopScreenShareBtn');
        const screenShareActiveDot = document.getElementById('screenShareActiveDot');

        function isOtherSharing() {
            if (!room) return false;
            for (const p of room.remoteParticipants.values()) {
                const pub = p.getTrackPublication(LiveKit.Track.Source.ScreenShare);
                if (pub && pub.track) return true;
            }
            return false;
        }

        async function toggleScreenShare() {
            if (isScreenSharing) {
                await stopScreenShare();
                return;
            }
            if (isOtherSharing()) {
                if (!confirm('Peserta lain sedang share layar. Ambil alih?')) return;
                sendBroadcast({ type: 'screen-share-takeover' });
            }
            try {
                await room.localParticipant.setScreenShareEnabled(true);
                isScreenSharing = true;
                bgDirty = true;
                screenShareBtn.classList.add('text-green-400');
                screenShareBtn.classList.remove('text-white');
                if (screenShareActiveDot) screenShareActiveDot.classList.remove('hidden');
                sendBroadcast({ type: 'screen-share-start', name: authName, sender_id: currentUserId });
                // Get the screen share track for local preview
                const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.ScreenShare);
                if (pub && pub.track) {
                    screenShareStream = new MediaStream();
                    const tracks = pub.track.mediaStream?.getVideoTracks() || [];
                    tracks.forEach(t => {
                        t.addEventListener('ended', () => setTimeout(() => stopScreenShare(), 500));
                        screenShareStream.addTrack(t);
                    });
                    showLocalScreenShareUI(true, tracks[0]);
                } else {
                    showLocalScreenShareUI(true);
                }
            } catch (e) {
                if (e.name !== 'NotAllowedError' && e.name !== 'AbortError') console.warn('Screen share failed:', e);
            }
        }

        async function stopScreenShare() {
            console.log('stopScreenShare called, reason:', new Error().stack);
            if (room) {
                await room.localParticipant.setScreenShareEnabled(false).catch(() => {});
            }
            if (screenShareStream) {
                screenShareStream.getTracks().forEach(t => t.stop());
                screenShareStream = null;
            }
            isScreenSharing = false;
            bgDirty = true;
            screenShareBtn.classList.remove('text-green-400');
            screenShareBtn.classList.add('text-white');
            if (screenShareActiveDot) screenShareActiveDot.classList.add('hidden');
            sendBroadcast({ type: 'screen-share-stop' });
            showLocalScreenShareUI(false);
            hideScreenShare();
        }

        function showLocalScreenShareUI(showing, track) {
            if (showing && track) {
                screenShareContainer.classList.remove('hidden');
                screenShareContainer.style.display = 'flex';
                screenShareContainer.style.alignItems = 'center';
                screenShareContainer.style.justifyContent = 'center';
                screenShareVideo.srcObject = new MediaStream([track]);
                if (screenShareLabel) screenShareLabel.textContent = 'Anda sedang share layar';
                if (stopScreenShareBtn) stopScreenShareBtn.classList.remove('hidden');
            } else {
                screenShareContainer.classList.add('hidden');
                screenShareContainer.style.display = '';
                screenShareVideo.srcObject = null;
                if (stopScreenShareBtn) stopScreenShareBtn.classList.add('hidden');
            }
        }

        function showRemoteScreenShare(track, participant) {
            const name = participant.name || participant.identity || 'Participant';
            hideScreenShare();
            screenShareContainer.classList.remove('hidden');
            screenShareContainer.style.display = 'flex';
            screenShareContainer.style.alignItems = 'center';
            screenShareContainer.style.justifyContent = 'center';
            track.attach(screenShareVideo);
            bgDirty = true;
            if (screenShareLabel) screenShareLabel.textContent = name + ' sedang share layar';
            if (stopScreenShareBtn) stopScreenShareBtn.classList.add('hidden');
        }

        // ======================== PIN VIEW ========================
        function togglePin(identity) {
            const idx = pinnedIdentities.indexOf(identity);
            if (idx >= 0) {
                pinnedIdentities.splice(idx, 1);
            } else {
                pinnedIdentities.push(identity);
            }
            updateParticipantUI();
            updatePinIndicators();
        }

        function isPinned(identity) {
            return pinnedIdentities.indexOf(identity) >= 0;
        }

        function updatePinIndicators() {
            // Toggle pinned-card class on video cards
            document.querySelectorAll('[id^="remote-card-"]').forEach(card => {
                const id = card.dataset?.identity || '';
                card.classList.toggle('pinned-card', isPinned(id));
            });
            const localCard = document.getElementById('localVideoContainer');
            if (localCard) {
                localCard.classList.toggle('pinned-card', isPinned(String(currentUserId)));
            }
            // Toggle pin button highlights
            document.querySelectorAll('[id^="pin-btn-"]').forEach(el => {
                el.classList.toggle('active', isPinned(el.dataset.identity));
            });
            const pinSsBtn = document.getElementById('pinScreenShareBtn');
            if (pinSsBtn) {
                pinSsBtn.classList.toggle('active', pinnedIdentities.indexOf('screen-share') >= 0);
            }
        }

        // ======================== CONTEXT MENU ========================
        let contextTargetIdentity = null;

        function showContextMenu(event, identity, displayName) {
            event.preventDefault();
            contextTargetIdentity = identity;
            const menu = document.getElementById('contextMenu');
            if (!menu) return;
            const pinBtn = document.getElementById('contextPinBtn');
            if (pinBtn) {
                if (isPinned(identity)) {
                    pinBtn.innerHTML = '📌 Unpin ' + escapeHtml(displayName);
                } else {
                    pinBtn.innerHTML = '📌 Pin ' + escapeHtml(displayName);
                }
                pinBtn.onclick = () => { togglePin(identity); hideContextMenu(); };
            }
            menu.classList.remove('hidden');
            menu.style.left = Math.min(event.clientX, window.innerWidth - 150) + 'px';
            menu.style.top = Math.min(event.clientY, window.innerHeight - 80) + 'px';
        }

        function hideContextMenu() {
            const menu = document.getElementById('contextMenu');
            if (menu) menu.classList.add('hidden');
            contextTargetIdentity = null;
        }

        // Context menu via event delegation on participantList
        const participantListEl = document.getElementById('participantList');
        if (participantListEl) {
            participantListEl.addEventListener('contextmenu', (e) => {
                const item = e.target.closest('[data-identity]');
                if (!item) return;
                e.preventDefault();
                const identity = item.dataset.identity;
                const nameSpan = item.querySelector('span');
                const displayName = nameSpan?.textContent?.trim() || identity;
                showContextMenu(e, identity, displayName);
            });
        }
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#contextMenu')) hideContextMenu();
        });

        function hideScreenShare() {
            if (isScreenSharing) return;
            screenShareContainer.classList.add('hidden');
            screenShareContainer.style.display = '';
            screenShareVideo.srcObject = null;
            bgDirty = true;
        }

        if (screenShareBtn) {
            screenShareBtn.addEventListener('click', toggleScreenShare);
        }
        if (stopScreenShareBtn) {
            stopScreenShareBtn.addEventListener('click', () => stopScreenShare());
        }
        const pinScreenShareBtn = document.getElementById('pinScreenShareBtn');
        if (pinScreenShareBtn) {
            pinScreenShareBtn.addEventListener('click', () => togglePin('screen-share'));
        }

        // Layout selector
        if (layoutBtn && layoutDropdown) {
            layoutBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                layoutDropdown.classList.toggle('hidden');
                setTimeout(() => layoutDropdown.classList.toggle('opacity-0'), 10);
            });
            document.addEventListener('click', (e) => {
                if (!layoutBtn.contains(e.target) && !layoutDropdown.contains(e.target) && !layoutDropdown.classList.contains('hidden')) {
                    layoutDropdown.classList.add('opacity-0');
                    setTimeout(() => layoutDropdown.classList.add('hidden'), 300);
                }
            });
            layoutDropdown.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', () => {
                    applyLayout(btn.dataset.layout);
                    layoutDropdown.classList.add('opacity-0');
                    setTimeout(() => layoutDropdown.classList.add('hidden'), 300);
                });
            });
            // Set initial active state
            layoutDropdown.querySelector(`[data-layout="${currentLayout}"]`)?.classList.add('active-layout');
        }

        // Screen recording
        if (recordScreenBtn) {
            recordScreenBtn.addEventListener('click', async () => {
                if (isRecordingScreen) {
                    await stopScreenRecording();
                } else {
                    await startScreenRecording();
                }
            });
        }

        // Spotlight: click overlay to switch spotlight target
        document.addEventListener('click', (e) => {
            const overlayCard = e.target.closest('.spotlight-overlay');
            if (overlayCard && currentLayout === 'spotlight') {
                spotlightTargetIdentity = overlayCard.dataset.identity;
                updateParticipantUI();
            }
        });

        // Listen for screen-share takeover from others via Echo
        // (handled inside subscribeEchoChannel's WebRTCSignal listener)

        // ======================== ECHO / REVERB SIGNALING ========================
        async function subscribeEchoChannel() {
            while (!window.Echo) {
                await new Promise(r => setTimeout(r, 50));
            }
            const pusher = window.Echo?.connector?.pusher;
            if (pusher?.connection) {
                pusher.connection.bind('connected', () => setConnectionStatus('LiveKit: terhubung', 'text-emerald-400'));
                pusher.connection.bind('disconnected', () => setConnectionStatus('LiveKit: terputus', 'text-amber-300'));
            }
            const channel = window.Echo.private('meeting.' + meetingId);
            channel.listen('.WebRTCSignal', async (e) => {
                const data = e.data ?? {};
                if (data.type === 'transcription') {
                    if (data.sender_id && Number(data.sender_id) === currentUserId) return;
                    appendTranscriptMessage(data.sender_id, data.sender_name, data.text);
                    return;
                }
                if (data.type === 'start-recording-broadcast') {
                    if (transcriptMessages) transcriptMessages.innerHTML = '';
                    if (showNotulensiBtn) showNotulensiBtn.classList.add('hidden');
                    if (pdfBtn) pdfBtn.classList.add('opacity-40', 'pointer-events-none');
                    if (!liveTranscriptionActive) {
                        liveTranscriptionActive = true;
                        updateSidebarStatus('Menerima transkrip...', 'text-emerald-400', 'bg-emerald-500');
                    }
                    const dot = document.getElementById('aiNotulenActiveDot');
                    if (dot) dot.classList.remove('hidden');
                    return;
                }
                if (data.type === 'stop-recording-broadcast') {
                    if (liveTranscriptionActive) {
                        stopLiveTranscription();
                    }
                    const dot = document.getElementById('aiNotulenActiveDot');
                    if (dot) dot.classList.add('hidden');
                    return;
                }
                if (data.type === 'camera-toggle') {
                    const identity = String(data.sender_id);
                    const safeKey = identity.replace(/[^a-zA-Z0-9_-]/g, '_');
                    const avatar = document.getElementById(`remote-avatar-${safeKey}`);
                    if (avatar) {
                        if (data.isOff) avatar.classList.remove('hidden');
                        else avatar.classList.add('hidden');
                    }
                }
                if (data.type === 'screen-share-start' || data.type === 'screen-share-takeover') {
                    if (data.sender_id && Number(data.sender_id) === currentUserId) return;
                    if (isScreenSharing) stopScreenShare();
                }
                if (data.type === 'screen-share-stop') {
                    if (!isScreenSharing) hideScreenShare();
                }
            });
        }

        // ======================== LIVE TRANSCRIPTION ========================
        async function connectWhisperSocket() {
            return new Promise((resolve, reject) => {
                if (whisperSocket && whisperSocket.readyState === WebSocket.OPEN) {
                    resolve(); return;
                }
                whisperRequestQueue = [];
                whisperSocket = new WebSocket(whisperWsUrl);
                whisperSocket.binaryType = 'arraybuffer';
                whisperSocket.onopen = () => {
                    isWhisperSocketOpen = true;
                    updateSidebarStatus('Socket terbuka', 'text-emerald-400', 'bg-emerald-500');
                    resolve();
                };
                whisperSocket.onclose = () => {
                    isWhisperSocketOpen = false;
                    updateSidebarStatus('Koneksi putus', 'text-amber-400', 'bg-amber-500');
                };
                whisperSocket.onerror = (err) => { reject(err); };
                whisperSocket.onmessage = (event) => {
                    try {
                        const queued = whisperRequestQueue.shift();
                        const data = JSON.parse(event.data);
                        if (data.status === 'success' && data.text && data.text.trim() !== '') {
                            appendTranscriptMessage(queued.userId, queued.name, data.text.trim());
                            syncTranscriptToLaravel(data.text.trim(), queued.userId, queued.name);
                        }
                    } catch (e) { console.error(e); }
                };
            });
        }

        function sendAccumulatedPcmForSpeaker(state) {
            if (state.pcmBuffer.length === 0) return;
            if (!whisperSocket || whisperSocket.readyState !== WebSocket.OPEN) {
                state.pcmBuffer = [];
                return;
            }
            let totalSamples = 0;
            for (let arr of state.pcmBuffer) totalSamples += arr.length;
            const int16Array = new Int16Array(totalSamples);
            let offset = 0;
            for (let floatArr of state.pcmBuffer) {
                for (let i = 0; i < floatArr.length; i++) {
                    let s = Math.max(-1, Math.min(1, floatArr[i]));
                    int16Array[offset++] = s < 0 ? s * 0x8000 : s * 0x7FFF;
                }
            }
            whisperRequestQueue.push({ userId: state.userId, name: state.name });
            whisperSocket.send(int16Array.buffer);
            state.pcmBuffer = [];
        }

        function startTrackVAD(state) {
            const VAD_THRESHOLD = 0.0002;
            const HANGOVER_FRAMES = 10;

            state.processor.onaudioprocess = (event) => {
                if (!liveTranscriptionActive) return;
                const inputData = event.inputBuffer.getChannelData(0);
                let sum = 0;
                for (let i = 0; i < inputData.length; i++) sum += inputData[i] * inputData[i];
                const energy = sum / inputData.length;

                if (energy > VAD_THRESHOLD) {
                    state.isSpeaking = true;
                    state.silenceFrames = 0;
                } else {
                    state.silenceFrames++;
                    if (state.silenceFrames > HANGOVER_FRAMES) state.isSpeaking = false;
                }

                if (state.isSpeaking) {
                    state.pcmBuffer.push(new Float32Array(inputData));
                    if (!state.wasSpeaking) {
                        state.wasSpeaking = true;
                        updateSidebarStatus('Mendengarkan (' + state.name + ')', 'text-emerald-400', 'bg-emerald-500');
                    }
                    if (state.pcmBuffer.length >= 28) sendAccumulatedPcmForSpeaker(state);
                } else {
                    if (state.wasSpeaking) {
                        state.wasSpeaking = false;
                        updateSidebarStatus('Memproses...', 'text-indigo-400', 'bg-indigo-500');
                        sendAccumulatedPcmForSpeaker(state);
                    }
                }
            };
        }

        async function startTrackAudioCapture(state, audioTracks) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            state.audioContext = new AudioContextClass({ sampleRate: 16000 });
            const stream = new MediaStream(audioTracks);
            state.source = state.audioContext.createMediaStreamSource(stream);
            state.processor = state.audioContext.createScriptProcessor(4096, 1, 1);
            startTrackVAD(state);
            state.source.connect(state.processor);
            state.processor.connect(state.audioContext.destination);
            await state.audioContext.resume();
        }

        async function startLiveTranscription() {
            await connectWhisperSocket();

            // 1. Process local participant
            if (localStream) {
                const audioTracks = localStream.getAudioTracks();
                if (audioTracks.length > 0 && audioTracks[0].enabled) {
                    const state = createTrackTranscriber('local_' + currentUserId, currentUserId, authName);
                    await startTrackAudioCapture(state, audioTracks);
                }
            }

            // 2. Process all remote participants
            if (room) {
                room.remoteParticipants.forEach((participant) => {
                    const audioPub = participant.getTrackPublication(LiveKit.Track.Source.Microphone);
                    if (audioPub && audioPub.track) {
                        const tracks = [];
                        if (audioPub.track.mediaStream) {
                            audioPub.track.mediaStream.getAudioTracks().forEach(t => tracks.push(t));
                        }
                        if (tracks.length === 0 && audioPub.track.mediaStreamTrack) {
                            tracks.push(audioPub.track.mediaStreamTrack);
                        }
                        if (tracks.length > 0) {
                            const identity = participant.identity;
                            const userId = Number(identity) || identity;
                            const displayName = participant.name || identity;
                            const state = createTrackTranscriber(identity, userId, displayName);
                            startTrackAudioCapture(state, tracks);
                        }
                    }
                });
            }

            liveTranscriptionActive = true;
            updateSidebarStatus('Mendengarkan (Multi)', 'text-emerald-400', 'bg-emerald-500');
        }

        function stopLiveTranscription() {
            liveTranscriptionActive = false;
            removeAllTranscribers();
            whisperRequestQueue = [];
            lastSpeakerId = null;
            lastMessageElement = null;
            if (whisperSocket && whisperSocket.readyState === WebSocket.OPEN) {
                whisperSocket.close();
                whisperSocket = null;
            }
            isWhisperSocketOpen = false;
            updateSidebarStatus('Mati', 'text-gray-500', 'bg-gray-500');
        }

        // ======================== RECORDING HELPERS ========================
        function updateRecordingParticipants() {
            const speakers = recordingActiveSpeakers;
            const speakerIds = new Set(speakers.filter(p => !p.isLocal).map(p => p.identity));

            recordingVideoCache.forEach((entry) => {
                entry.isSpeaking = speakerIds.has(entry.identity);
            });

            const ordered = [];
            const added = new Set();

            for (const p of speakers) {
                if (p.isLocal) continue;
                const entry = recordingVideoCache.get(p.identity);
                if (entry && !added.has(p.identity)) {
                    ordered.push(entry);
                    added.add(p.identity);
                }
            }

            if (ordered.length < 4) {
                for (const id of recordingSpeakerQueue) {
                    if (added.has(id)) continue;
                    const entry = recordingVideoCache.get(id);
                    if (entry) {
                        ordered.push(entry);
                        added.add(id);
                    }
                }
            }

            if (ordered.length < 4) {
                const localEntry = recordingVideoCache.get('local_' + currentUserId);
                if (localEntry && !added.has('local_' + currentUserId)) {
                    ordered.push(localEntry);
                    added.add('local_' + currentUserId);
                }
            }

            if (ordered.length < 4) {
                recordingVideoCache.forEach((entry, id) => {
                    if (!added.has(id) && ordered.length < 4) {
                        ordered.push(entry);
                        added.add(id);
                    }
                });
            }

            recordingParticipants = ordered.slice(0, 4);
            bgDirty = true;
        }

        function drawAvatar(ctx, name, x, y, w, h) {
            ctx.fillStyle = '#374151';
            ctx.beginPath();
            ctx.roundRect(x, y, w, h, 8);
            ctx.fill();
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 48px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText((name || 'P').charAt(0).toUpperCase(), x + w / 2, y + h / 2);
            ctx.textBaseline = 'alphabetic';
        }

        function drawNameLabel(ctx, name, x, y, w, h) {
            ctx.fillStyle = 'rgba(0,0,0,0.6)';
            ctx.beginPath();
            ctx.roundRect(x, y + h - 28, w, 28, 8);
            ctx.fill();
            ctx.fillStyle = '#fff';
            ctx.font = '13px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(name || '-', x + w / 2, y + h - 8);
        }

        function drawSpeakingRing(ctx, x, y, w, h) {
            ctx.strokeStyle = '#22c55e';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.roundRect(x - 1.5, y - 1.5, w + 3, h + 3, 9);
            ctx.stroke();
        }

        // ======================== SCREEN RECORDING ========================
        async function startScreenRecording() {
            if (isRecordingScreen) return;
            recordingCanvas = document.getElementById('recordingCanvas');
            if (!recordingCanvas) { alert('Canvas not found'); return; }
            recordingCanvasCtx = recordingCanvas.getContext('2d');
            recordingChunks = [];

            // Cache local participant video
            if (localVideo) {
                recordingVideoCache.set('local_' + currentUserId, {
                    videoEl: localVideo,
                    name: authName,
                    identity: 'local_' + currentUserId,
                    isSpeaking: false
                });
            }

            // Init offscreen canvases for layer compositing
            const W = recordingCanvas.width;
            const H = recordingCanvas.height;
            recordingBgCanvas = document.createElement('canvas');
            recordingBgCanvas.width = W;
            recordingBgCanvas.height = H;
            recordingThumbCanvas = document.createElement('canvas');
            recordingThumbCanvas.width = W;
            recordingThumbCanvas.height = H;
            bgDirty = true;
            thumbnailsDirty = true;

            // Collect audio streams from all participants
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                recordingAudioDestination = audioCtx.createMediaStreamDestination();

                if (localStream) {
                    const localTracks = localStream.getAudioTracks();
                    if (localTracks.length > 0) {
                        const localSource = audioCtx.createMediaStreamSource(new MediaStream(localTracks));
                        localSource.connect(recordingAudioDestination);
                    }
                }

                if (room) {
                    room.remoteParticipants.forEach((participant) => {
                        const audioPub = participant.getTrackPublication(LiveKit.Track.Source.Microphone);
                        if (audioPub && audioPub.track) {
                            const ms = audioPub.track.mediaStream;
                            if (ms && ms.getAudioTracks().length > 0) {
                                const source = audioCtx.createMediaStreamSource(new MediaStream(ms.getAudioTracks()));
                                source.connect(recordingAudioDestination);
                            }
                        }
                    });
                }

                recordingAudioMixer = audioCtx;
            } catch (e) {
                console.warn('Audio mixer error, recording without audio:', e);
            }

            let recordingGridLayout = [];

            function computeGridLayout(count) {
                const layouts = [];
                if (count === 1) {
                    layouts.push({ x: 0, y: 0, w: W, h: H });
                } else if (count === 2) {
                    layouts.push({ x: 0, y: 0, w: W / 2, h: H });
                    layouts.push({ x: W / 2, y: 0, w: W / 2, h: H });
                } else if (count === 3) {
                    layouts.push({ x: 0, y: 0, w: W / 2, h: H / 2 });
                    layouts.push({ x: W / 2, y: 0, w: W / 2, h: H / 2 });
                    layouts.push({ x: 0, y: H / 2, w: W, h: H / 2 });
                } else {
                    layouts.push({ x: 0, y: 0, w: W / 2, h: H / 2 });
                    layouts.push({ x: W / 2, y: 0, w: W / 2, h: H / 2 });
                    layouts.push({ x: 0, y: H / 2, w: W / 2, h: H / 2 });
                    layouts.push({ x: W / 2, y: H / 2, w: W / 2, h: H / 2 });
                }
                return layouts;
            }

            function renderBackground() {
                const ctx = recordingBgCanvas.getContext('2d');
                ctx.clearRect(0, 0, W, H);
                ctx.fillStyle = '#1a1a2e';
                ctx.fillRect(0, 0, W, H);

                const ssVideo = document.getElementById('screenShareVideo');
                const hasScreenShare = ssVideo && ssVideo.srcObject && !screenShareContainer?.classList.contains('hidden');
                if (hasScreenShare && ssVideo.readyState >= 2) {
                    ctx.drawImage(ssVideo, 0, 0, W, H);
                } else {
                    const participants = recordingParticipants;
                    const count = Math.min(participants.length, 4);
                    if (count > 0) {
                        recordingGridLayout = computeGridLayout(count);
                        for (let i = 0; i < count; i++) {
                            const p = participants[i];
                            const l = recordingGridLayout[i];
                            ctx.fillStyle = '#374151';
                            ctx.fillRect(l.x, l.y, l.w, l.h);
                            ctx.fillStyle = '#fff';
                            ctx.font = 'bold 64px Inter, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText((p.name || 'P').charAt(0).toUpperCase(), l.x + l.w / 2, l.y + l.h / 2);
                            ctx.textBaseline = 'alphabetic';
                            ctx.fillStyle = 'rgba(0,0,0,0.5)';
                            ctx.fillRect(l.x, l.y + l.h - 30, l.w, 30);
                            ctx.fillStyle = '#fff';
                            ctx.font = '14px Inter, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.fillText(p.name || '-', l.x + l.w / 2, l.y + l.h - 9);
                        }
                    } else {
                        ctx.fillStyle = '#333';
                        ctx.font = 'bold 48px Inter, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('{{ $meeting->nama_rapat }}', W / 2, H / 2 - 20);
                        ctx.font = '24px Inter, sans-serif';
                        ctx.fillStyle = '#666';
                        ctx.fillText(new Date().toLocaleString(), W / 2, H / 2 + 40);
                    }
                }
                bgDirty = false;
            }

            function renderFrame() {
                if (bgDirty) renderBackground();

                const ctx = recordingCanvasCtx;
                ctx.clearRect(0, 0, W, H);
                ctx.drawImage(recordingBgCanvas, 0, 0);

                const ssVideo = document.getElementById('screenShareVideo');
                const hasScreenShare = ssVideo && ssVideo.srcObject && !screenShareContainer?.classList.contains('hidden');
                if (!hasScreenShare || ssVideo.readyState < 2) {
                    const participants = recordingParticipants;
                    const count = Math.min(participants.length, 4);
                    if (count > 0 && recordingGridLayout.length >= count) {
                        const layout = recordingGridLayout;
                        for (let i = 0; i < count; i++) {
                            const p = participants[i];
                            const l = layout[i];
                            const videoEl = p.videoEl;
                            if (videoEl && videoEl.readyState >= 2) {
                                ctx.drawImage(videoEl, l.x, l.y, l.w, l.h);
                            }
                        }
                    }
                }

                const fc = recordingFrameCounter++;
                if (fc % 30 < 15) {
                    ctx.fillStyle = '#ef4444';
                    ctx.beginPath();
                    ctx.arc(40, 40, 14, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.fillStyle = '#fff';
                    ctx.font = 'bold 14px Inter, sans-serif';
                    ctx.textAlign = 'left';
                    ctx.fillText('REC', 60, 47);
                }
            }

            let recordingFrameCounter = 0;
            isRecordingScreen = true;
            recordingRenderTimer = setInterval(() => {
                if (!isRecordingScreen) return;
                renderFrame();
            }, 66);

            const videoStream = recordingCanvas.captureStream(15);
            let combinedStream;
            if (recordingAudioDestination) {
                const audioTracks = recordingAudioDestination.stream.getAudioTracks();
                if (audioTracks.length > 0) {
                    combinedStream = new MediaStream([
                        ...videoStream.getVideoTracks(),
                        audioTracks[0]
                    ]);
                } else {
                    combinedStream = videoStream;
                }
            } else {
                combinedStream = videoStream;
            }

            const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp8,opus')
                ? 'video/webm;codecs=vp8,opus'
                : 'video/webm';
            recordingMediaRecorder = new MediaRecorder(combinedStream, { mimeType });
            recordingMediaRecorder.ondataavailable = (e) => {
                if (e.data.size > 0) recordingChunks.push(e.data);
            };
            recordingMediaRecorder.onstop = () => {
                if (recordingChunks.length > 0) {
                    const blob = new Blob(recordingChunks, { type: 'video/webm' });
                    uploadScreenRecording(blob);
                }
            };
            recordingMediaRecorder.start(1000);

            if (recordIconDefault) recordIconDefault.classList.add('hidden');
            if (recordIconActive) recordIconActive.classList.remove('hidden');
            if (recordActiveDot) recordActiveDot.classList.remove('hidden');
            if (recordScreenBtn) recordScreenBtn.querySelector('span')?.classList.add('text-red-400');
        }

        function stopScreenRecording() {
            if (!isRecordingScreen) return;
            isRecordingScreen = false;
            if (recordingRenderTimer) { clearInterval(recordingRenderTimer); recordingRenderTimer = null; }
            if (recordingMediaRecorder && recordingMediaRecorder.state !== 'inactive') {
                recordingMediaRecorder.stop();
            }
            if (recordingAudioMixer) {
                recordingAudioMixer.close().catch(() => {});
                recordingAudioMixer = null;
            }
            recordingCanvasCtx = null;
            recordingAudioDestination = null;
            recordingBgCanvas = null;
            recordingThumbCanvas = null;
            recordingVideoCache.delete('local_' + currentUserId);

            if (recordIconDefault) recordIconDefault.classList.remove('hidden');
            if (recordIconActive) recordIconActive.classList.add('hidden');
            if (recordActiveDot) recordActiveDot.classList.add('hidden');
            if (recordScreenBtn) recordScreenBtn.querySelector('span')?.classList.remove('text-red-400');
        }

        async function uploadScreenRecording(blob) {
            try {
                const formData = new FormData();
                formData.append('recording', blob, `meeting_${meetingId}_${Date.now()}.webm`);
                formData.append('duration_seconds', Math.floor(blob.size / 500000)); // estimate
                const res = await fetch(baseUrl + '/upload-screen-recording', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    alert('Rekaman terupload! Pipeline diproses di background.');
                } else {
                    alert('Gagal upload: ' + (data.message || 'Unknown error'));
                }
            } catch (e) {
                alert('Gagal upload rekaman: ' + e.message);
            }
            recordingChunks = [];
        }

        // Keep old functions for backward compat
        function getVisibleParticipants() {
            const localEl = document.getElementById('localVideoContainer');
            const remotes = Array.from(document.querySelectorAll('#remoteVideos > [id^="remote-card-"]'));
            const allCards = [];
            if (localEl) allCards.push(localEl);
            const pinned = [];
            const unpinned = [];
            remotes.forEach(el => {
                const id = el.dataset?.identity || '';
                const pinIdx = pinnedIdentities.indexOf(id);
                if (pinIdx >= 0) {
                    pinned[pinIdx] = el;
                } else {
                    unpinned.push(el);
                }
            });
            pinned.forEach(el => { if (el) allCards.push(el); });
            unpinned.forEach(el => allCards.push(el));
            return allCards;
        }

        // ======================== PIPELINE & NOTULENSI ========================
        let pipelinePollTimer = null;

        function applyPipelinePayload(data) {
            const st = data.pipeline_status || 'idle';
            const stage = data.pipeline_stage ? ` (${data.pipeline_stage})` : '';
            const err = data.pipeline_error ? ` — ${data.pipeline_error}` : '';
            const pipelineStatusEl = document.getElementById('pipelineStatus');
            if (pipelineStatusEl) {
                pipelineStatusEl.textContent = 'Pipeline: ' + st + stage + err;
                pipelineStatusEl.className = 'text-xs ' + (st === 'failed' ? 'text-red-400' : st === 'completed' ? 'text-emerald-400' : 'text-amber-200');
            }
            if (pdfBtn && data.has_pdf) {
                pdfBtn.classList.remove('opacity-40', 'pointer-events-none');
                pdfBtn.setAttribute('href', notulensiPdfUrl);
            }
        }

        async function refreshPipelineStatus() {
            try {
                const res = await fetch(baseUrl + '/pipeline-status', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                applyPipelinePayload(data);
                if (data.pipeline_status !== 'processing' && pipelinePollTimer) {
                    clearInterval(pipelinePollTimer);
                    pipelinePollTimer = null;
                }
            } catch (e) { console.warn(e); }
        }

        function startPipelinePolling() {
            if (pipelinePollTimer) clearInterval(pipelinePollTimer);
            pipelinePollTimer = setInterval(refreshPipelineStatus, 4000);
            refreshPipelineStatus();
        }

        async function triggerGeminiNotulensi() {
            showAiLoading(true);
            try {
                const res = await fetch(baseUrl + '/generate-notulensi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                showAiLoading(false);
                if (!res.ok) throw new Error(data.message || 'Gagal membuat notulensi');
                if (!data.notulensi || !data.notulensi.id) {
                    throw new Error('Notulensi tidak tersimpan ke database (ID kosong)');
                }
                renderNotulensiModal(data.notulensi, data.pdf_url);
                if (showNotulensiBtn) showNotulensiBtn.classList.remove('hidden');
                if (pdfBtn) {
                    pdfBtn.classList.remove('opacity-40', 'pointer-events-none');
                    pdfBtn.setAttribute('href', data.pdf_url);
                }
                openNotulensiModal(true);
            } catch (err) {
                showAiLoading(false);
                alert(err.message);
            }
        }

        function showAiLoading(show) {
            const overlay = document.getElementById('aiLoadingOverlay');
            if (!overlay) return;
            if (show) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 50);
            } else {
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function openNotulensiModal(open) {
            if (!notulensiModal) return;
            if (open) {
                notulensiModal.classList.remove('hidden');
                setTimeout(() => {
                    notulensiModal.classList.add('opacity-100');
                    notulensiModal.firstElementChild.classList.remove('scale-95');
                    notulensiModal.firstElementChild.classList.add('scale-100');
                }, 50);
            } else {
                notulensiModal.classList.remove('opacity-100');
                notulensiModal.firstElementChild.classList.remove('scale-100');
                notulensiModal.firstElementChild.classList.add('scale-95');
                setTimeout(() => notulensiModal.classList.add('hidden'), 300);
            }
        }

        function renderNotulensiModal(notulensi, pdfUrl) {
            document.getElementById('modalRingkasan').textContent = notulensi.ringkasan || '-';
            document.getElementById('modalPdfBtn').setAttribute('href', pdfUrl);
            const s = notulensi.structured_summary || {};
            const topikEl = document.getElementById('modalTopik');
            topikEl.innerHTML = (s.topik_dibahas || []).map((t, i) =>
                `<li class="flex items-start gap-2.5"><span class="flex-shrink-0 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center mt-0.5" style="background:rgba(16,185,129,0.12);color:#34d399">${i + 1}</span><span class="text-sm leading-relaxed text-gray-300">${escapeHtml(t)}</span></li>`
            ).join('') || '<li class="text-sm text-gray-500 italic">-</li>';
            document.getElementById('modalKeputusan').innerHTML = (s.keputusan || []).map(k =>
                `<li class="flex items-start gap-2.5"><svg class="flex-shrink-0 w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:#fbbf24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-sm leading-relaxed text-gray-300">${escapeHtml(k)}</span></li>`
            ).join('') || '<li class="text-sm text-gray-500 italic">-</li>';
            const actionItems = s.action_items || [];
            const tbody = document.getElementById('modalActionItems');
            if (actionItems.length) tbody.innerHTML = actionItems.map(ai =>
                `<tr><td class="px-4 py-3 text-sm text-gray-300">${escapeHtml(ai.task||'-')}</td><td class="px-4 py-3"><span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full" style="background:rgba(139,92,246,0.1);color:#a78bfa"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>${escapeHtml(ai.pic||'-')}</span></td><td class="px-4 py-3"><span class="inline-flex items-center gap-1.5 text-xs" style="color:#9ca3af"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>${escapeHtml(ai.deadline||'-')}</span></td></tr>`
            ).join('');
            else tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500 italic">-</td></tr>';
            document.getElementById('modalRisiko').innerHTML = (s.risiko_catatan || []).map(r =>
                `<li class="flex items-start gap-2.5"><svg class="flex-shrink-0 w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#9ca3af"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-sm leading-relaxed text-gray-300">${escapeHtml(r)}</span></li>`
            ).join('') || '<li class="text-sm text-gray-500 italic">-</li>';
        }

        // ======================== EVENT LISTENERS ========================
        if (enableAudioBtn) enableAudioBtn.addEventListener('click', () => {
            document.querySelectorAll('#remoteVideos video').forEach(v => {
                v.muted = false;
                v.play().catch(e => console.warn);
            });
            audioEnabledByUser = true;
            enableAudioBtn.style.display = 'none';
        });

        if (muteBtn) {
            muteBtn.addEventListener('click', () => {
                isMuted = !isMuted;
                saveDeviceState();
                if (room) {
                    const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Microphone);
                    if (pub && pub.track) {
                        if (isMuted) { pub.track.mute().catch(e => console.warn(e)); }
                        else { pub.track.unmute().catch(e => console.warn(e)); }
                    }
                }
                if (localStream) {
                    localStream.getAudioTracks().forEach(t => t.enabled = !isMuted);
                }
                if (isMuted) {
                    muteBtn.classList.add('text-red-400');
                    muteBtn.classList.remove('text-white');
                } else {
                    muteBtn.classList.add('text-white');
                    muteBtn.classList.remove('text-red-400');
                }
            });
        }

        if (cameraBtn) {
            cameraBtn.addEventListener('click', () => {
                isCameraOff = !isCameraOff;
                saveDeviceState();
                if (room) {
                    const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Camera);
                    if (pub && pub.track) {
                        if (isCameraOff) { pub.track.mute().catch(e => console.warn(e)); }
                        else { pub.track.unmute().catch(e => console.warn(e)); }
                    }
                }
                if (localStream) {
                    localStream.getVideoTracks().forEach(t => t.enabled = !isCameraOff);
                }
                const localAvatar = document.getElementById('localAvatar');
                const localAvatarText = document.getElementById('localAvatarText');
                if (isCameraOff) {
                    cameraBtn.classList.add('text-red-400');
                    cameraBtn.classList.remove('text-white');
                    if (localAvatar) {
                        localAvatar.classList.remove('hidden');
                        if (localAvatarText) localAvatarText.textContent = authName.charAt(0).toUpperCase();
                    }
                    startAudioMonitor();
                } else {
                    cameraBtn.classList.add('text-white');
                    cameraBtn.classList.remove('text-red-400');
                    if (localAvatar) localAvatar.classList.add('hidden');
                    stopAudioMonitor();
                }
                sendBroadcast({ type: 'camera-toggle', isOff: isCameraOff });
            });
        }

        if (leaveBtn) leaveBtn.addEventListener('click', leaveMeeting);

        const participantBtn = document.getElementById('participantBtn');
        const participantSidebar = document.getElementById('participantSidebar');
        const closeParticipantBtn = document.getElementById('closeParticipantBtn');

        if (participantBtn && participantSidebar) {
            participantBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (participantSidebar.classList.contains('translate-x-full')) {
                    participantSidebar.classList.remove('translate-x-full');
                    updateParticipantUI();
                } else {
                    participantSidebar.classList.add('translate-x-full');
                }
            });
        }
        if (closeParticipantBtn && participantSidebar) {
            closeParticipantBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                participantSidebar.classList.add('translate-x-full');
            });
        }

        const aiNotulenTriggerBtn = document.getElementById('aiNotulenTriggerBtn');
        const confirmNotulenModal = document.getElementById('confirmNotulenModal');
        const cancelNotulenBtn = document.getElementById('cancelNotulenBtn');
        const simpanNotulenBtn = document.getElementById('simpanNotulenBtn');
        const aiNotulenActiveDot = document.getElementById('aiNotulenActiveDot');

        if (aiNotulenTriggerBtn) {
            aiNotulenTriggerBtn.addEventListener('click', async () => {
                if (liveTranscriptionActive) {
                    if (confirmNotulenModal) confirmNotulenModal.classList.remove('hidden');
                } else {
                    try {
                        await fetch('/meeting/{{ $meeting->id }}/start-recording', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        await startLiveTranscription();
                        sendBroadcast({ type: 'start-recording-broadcast' });
                        if (aiNotulenActiveDot) aiNotulenActiveDot.classList.remove('hidden');
                    } catch (err) {
                        alert('Gagal memulai notulensi: ' + err.message);
                    }
                }
            });
        }

        if (cancelNotulenBtn) {
            cancelNotulenBtn.addEventListener('click', () => {
                if (confirmNotulenModal) confirmNotulenModal.classList.add('hidden');
            });
        }

        if (simpanNotulenBtn) {
            simpanNotulenBtn.addEventListener('click', () => {
                if (confirmNotulenModal) confirmNotulenModal.classList.add('hidden');
                if (!liveTranscriptionActive) return;
                sendBroadcast({ type: 'stop-recording-broadcast' });
                stopLiveTranscription();
                if (aiNotulenActiveDot) aiNotulenActiveDot.classList.add('hidden');
                triggerGeminiNotulensi();
            });
        }

        if (toggleSidebarBtn) toggleSidebarBtn.addEventListener('click', () => {
            transcriptSidebar.classList.add('collapsed');
            openSidebarBtn.classList.remove('hidden');
        });
        if (openSidebarBtn) openSidebarBtn.addEventListener('click', () => {
            transcriptSidebar.classList.remove('collapsed');
            openSidebarBtn.classList.add('hidden');
            transcriptMessages.scrollTop = transcriptMessages.scrollHeight;
        });
        if (clearTranscriptBtn) clearTranscriptBtn.addEventListener('click', () => {
            if (confirm('Bersihkan transkrip?')) transcriptMessages.innerHTML =
                '<div id="emptyTranscriptMsg" class="text-gray-500 text-center py-8 italic text-xs">Belum ada transkripsi aktif.</div>';
        });

        const closeModalBtns = [document.getElementById('closeNotulensiModalBtn'), document.getElementById('closeNotulensiModalFooterBtn')];
        closeModalBtns.forEach(btn => btn && btn.addEventListener('click', () => openNotulensiModal(false)));
        if (notulensiModal) notulensiModal.addEventListener('click', (e) => {
            if (e.target === notulensiModal) openNotulensiModal(false);
        });

        // ======================== INIT ========================
        updateParticipantUI();
        connectToLiveKit();

        const shareBtn = document.getElementById('shareBtn');
        const sharePopup = document.getElementById('sharePopup');
        if (shareBtn && sharePopup) {
            shareBtn.addEventListener('click', () => {
                sharePopup.classList.toggle('hidden');
                setTimeout(() => { sharePopup.classList.toggle('opacity-0'); }, 10);
            });
            document.addEventListener('click', (e) => {
                if (!shareBtn.contains(e.target) && !sharePopup.contains(e.target) && !sharePopup.classList.contains('hidden')) {
                    sharePopup.classList.add('opacity-0');
                    setTimeout(() => { sharePopup.classList.add('hidden'); }, 300);
                }
            });
        }

        // ── Theme toggle (room) — always starts dark ──
        (function() {
            const html = document.documentElement;
            const toggle = document.getElementById('roomThemeToggle');
            const sunIcon = document.getElementById('roomThemeIconSun');
            const moonIcon = document.getElementById('roomThemeIconMoon');
            function setTheme(dark) {
                html.classList.toggle('dark', dark);
                if (sunIcon) sunIcon.classList.toggle('hidden', !dark);
                if (moonIcon) moonIcon.classList.toggle('hidden', dark);
            }
            html.classList.add('dark');
            if (sunIcon) sunIcon.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            if (toggle) toggle.addEventListener('click', () => setTheme(!html.classList.contains('dark')));
        })();
    </script>
@endsection
