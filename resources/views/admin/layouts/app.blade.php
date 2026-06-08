<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Meet BPS Admin</title>
    <link rel="icon" href="/images/logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        /* ===================== COLOR TOKENS ===================== */
        :root {
            --sidebar-bg:   #0b1739;
            --sidebar-w:    260px;
            --topbar-bg:    #0b1739;
            --content-bg:   #0f1e3d;
            --card-bg:      rgba(255,255,255,0.05);
            --card-border:  rgba(255,255,255,0.08);
            --card-shadow:  0 4px 24px rgba(0,0,0,0.3);
            --text-primary: #f0f4ff;
            --text-secondary:#94a3b8;
            --text-muted:   #5b6b8a;
            --active-bg:    linear-gradient(90deg,#1e40af,#2563eb);
            --active-color: #fff;
            --hover-bg:     rgba(255,255,255,0.07);
            --divider:      rgba(255,255,255,0.07);
            --accent:       #3b82f6;
            --scrollbar:    rgba(59,130,246,0.25);
        }

        html, body { height: 100%; margin: 0; }

        body {
            background: var(--content-bg);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===================== SCROLLBAR ===================== */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(59,130,246,0.45); }

        /* ===================== TOPBAR ===================== */
        #topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--divider);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px 0 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            padding: 0 20px;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-logo-wrap img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 8px;
        }
        .brand-text { line-height: 1.2; }
        .brand-name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.02em;
        }
        .brand-sub {
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hamburger-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: var(--text-secondary);
            border-radius: 8px;
            transition: background .2s, color .2s;
            display: flex;
            align-items: center;
        }
        .hamburger-btn:hover { background: var(--hover-bg); color: #fff; }

        .notif-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: var(--text-secondary);
            border-radius: 8px;
            transition: background .2s, color .2s;
            display: flex;
            align-items: center;
        }
        .notif-btn:hover { background: var(--hover-bg); color: #fff; }
        .notif-badge {
            position: absolute;
            top: 5px; right: 5px;
            width: 17px; height: 17px;
            background: #ef4444;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--topbar-bg);
        }

        .user-avatar-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 10px;
            transition: background .2s;
        }
        .user-avatar-btn:hover { background: var(--hover-bg); }
        .avatar-circle {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: #fff;
            flex-shrink: 0;
        }
        .user-info { text-align: left; }
        .user-name { font-size: 13px; font-weight: 600; color: #fff; line-height: 1.2; }
        .user-role { font-size: 11px; color: var(--text-muted); }
        .chevron-icon { color: var(--text-muted); }

        /* Dropdown */
        .user-dropdown {
            position: absolute;
            right: 0; top: calc(100% + 8px);
            width: 220px;
            background: #0e1f42;
            border: 1px solid var(--divider);
            border-radius: 14px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.5);
            overflow: hidden;
            z-index: 200;
        }
        .dropdown-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--divider);
        }
        .dropdown-header .dh-name { font-size: 13px; font-weight: 600; color: #fff; }
        .dropdown-header .dh-email { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .dropdown-body { padding: 6px; }
        .dd-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: background .15s, color .15s;
            cursor: pointer;
            border: none; background: none; width: 100%; text-align: left;
        }
        .dd-item:hover { background: var(--hover-bg); color: #fff; }
        .dd-item.danger { color: #f87171; }
        .dd-item.danger:hover { background: rgba(239,68,68,0.1); color: #ef4444; }
        .dd-sep { height: 1px; background: var(--divider); margin: 4px 0; }

        /* ===================== LAYOUT WRAPPER ===================== */
        #layout {
            display: flex;
            margin-top: 60px;
            min-height: calc(100vh - 60px);
        }

        /* ===================== SIDEBAR ===================== */
        #sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--divider);
            display: flex;
            flex-direction: column;
            padding: 16px 12px;
            overflow-y: auto;
            position: fixed;
            top: 60px; left: 0; bottom: 0;
            transition: transform .3s cubic-bezier(.4,0,.2,1), width .3s;
            z-index: 90;
        }
        #sidebar.collapsed {
            transform: translateX(calc(-1 * var(--sidebar-w)));
        }
        @media (max-width: 767px) {
            #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            #sidebar.open { transform: translateX(0); }
        }

        #main-content {
            flex: 1;
            min-width: 0;
            margin-left: var(--sidebar-w);
            padding: 24px;
            overflow-y: auto;
            transition: margin-left .3s cubic-bezier(.4,0,.2,1);
        }
        #main-content.sidebar-collapsed {
            margin-left: 0;
        }
        @media (max-width: 767px) {
            #main-content { margin-left: 0; }
        }

        /* Sidebar section labels */
        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 0 8px;
            margin: 14px 0 6px;
        }
        .sidebar-section-label:first-child { margin-top: 0; }

        /* Nav links */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: background .2s, color .2s, transform .15s;
            margin-bottom: 2px;
            cursor: pointer;
        }
        .nav-link:hover {
            background: var(--hover-bg);
            color: #fff;
        }
        .nav-link.active {
            background: linear-gradient(90deg, #1e40af, #2563eb);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
        }
        .nav-link svg { flex-shrink: 0; }

        /* ===================== CARDS ===================== */
        .card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform .25s, box-shadow .25s;
        }
        .card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.35); }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: #2563eb;
            color: #fff;
            border-radius: 10px;
            font-size: 14px; font-weight: 600;
            border: none; cursor: pointer;
            text-decoration: none;
            transition: background .2s, box-shadow .2s, transform .15s;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
            transform: translateY(-1px);
        }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: transparent;
            color: #fff;
            border-radius: 10px;
            font-size: 14px; font-weight: 500;
            border: 1px solid rgba(255,255,255,0.25); cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.08); }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--divider);
        }
        tbody td {
            padding: 11px 14px;
            font-size: 13px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--divider);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(255,255,255,0.03); }

        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px; font-weight: 600;
        }

        /* Alerts */
        .alert-success {
            padding: 12px 16px; margin-bottom: 16px;
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            border-radius: 10px;
            color: #34d399;
            display: flex; align-items: center; gap: 10px;
            font-size: 13px;
        }
        .alert-error {
            padding: 12px 16px; margin-bottom: 16px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            color: #f87171;
            display: flex; align-items: center; gap: 10px;
            font-size: 13px;
        }

        /* Input / Form */
        .input-field {
            width: 100%;
            padding: 9px 13px;
            border-radius: 9px;
            font-size: 13px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-primary);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-field:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .label {
            display: block;
            font-size: 12px; font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        .btn-danger {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px;
            background: #dc2626; color: #fff;
            border-radius: 8px; font-size: 13px; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .btn-danger:hover { background: #b91c1c; }

        /* Page header */
        .page-header { margin-bottom: 20px; }
        .page-header h1 { font-size: 20px; font-weight: 700; color: #fff; }
        .page-header p { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        /* Sidebar overlay on mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 80;
        }
        #sidebar-overlay.show { display: block; }

        /* Fade in animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .35s ease-out both; }
    </style>
</head>
<body>

{{-- =================== TOPBAR =================== --}}
<nav id="topbar">
    <div class="topbar-left">
        <button id="sidebarToggleBtn" class="hamburger-btn" aria-label="Toggle sidebar">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="brand-logo-wrap">
            <img src="{{ asset('images/logo.png') }}" alt="BPS Logo">
            <div class="brand-text">
                <div class="brand-name">MEET BPS</div>
                <div class="brand-sub">Internal Meeting</div>
            </div>
        </a>
    </div>

    <div class="topbar-right">
        {{-- Notification Bell --}}
        <button class="notif-btn" title="Notifikasi">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="notif-badge">3</span>
        </button>

        {{-- User Dropdown --}}
        <div class="relative" style="position:relative" x-data="{ open: false }">
            <button @click="open = !open" @keydown.escape.window="open = false"
                class="user-avatar-btn focus:outline-none">
                <div class="avatar-circle">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-info hidden sm:block">
                    <div class="user-name">{{ auth()->user()?->name ?? 'Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <svg class="chevron-icon w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="open = false"
                class="user-dropdown">
                <div class="dropdown-header">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="avatar-circle" style="width:38px;height:38px;font-size:13px;flex-shrink:0">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <div class="dh-name">{{ auth()->user()?->name }}</div>
                            <div class="dh-email">{{ auth()->user()?->email }}</div>
                        </div>
                    </div>
                </div>
                <div class="dropdown-body">
                    <a href="{{ route('profile.show') }}" class="dd-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>
                    <a href="{{ route('meeting.join.form') }}" class="dd-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Ke Halaman User
                    </a>
                    <div class="dd-sep"></div>
                    <a href="{{ route('logout') }}" class="dd-item danger">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Mobile sidebar overlay --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- =================== LAYOUT =================== --}}
<div id="layout">

    {{-- =================== SIDEBAR =================== --}}
    <aside id="sidebar">

        {{-- Dashboard Home --}}
        @can('admin_access_dashboard')
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            style="margin-bottom:8px">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        @endcan

        {{-- JADWAL --}}
        @canany(['admin_access_agendas', 'admin_access_meetings'])
        <div class="sidebar-section-label">Jadwal</div>

        @can('admin_access_agendas')
        <a href="{{ route('admin.agendas.index') }}"
            class="nav-link {{ request()->routeIs('admin.agendas.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Agendas
        </a>
        @endcan

        @can('admin_access_meetings')
        <a href="{{ route('admin.meetings.index') }}"
            class="nav-link {{ request()->routeIs('admin.meetings.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Meetings
        </a>
        @endcan
        @endcanany

        {{-- PENGARSIPAN --}}
        @canany(['admin_access_arsips', 'admin_access_rekaman_audio'])
        <div class="sidebar-section-label">Pengarsipan</div>

        @can('admin_access_arsips')
        <a href="{{ route('admin.arsips.index') }}"
            class="nav-link {{ request()->routeIs('admin.arsips.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            Arsips
        </a>
        @endcan

        @can('admin_access_rekaman_audio')
        <a href="{{ route('admin.rekaman-audio.index') }}"
            class="nav-link {{ request()->routeIs('admin.rekaman-audio.*') && !request('tipe') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
            </svg>
            Rekaman Audios
        </a>
        <a href="{{ route('admin.rekaman-audio.index', ['tipe' => 'video']) }}"
            class="nav-link {{ request('tipe') === 'video' ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Transkripsi
        </a>
        @endcan
        @endcanany

        {{-- ADMINISTRASI --}}
        @canany(['admin_access_users', 'admin_access_roles'])
        <div class="sidebar-section-label">Administrasi</div>

        @can('admin_access_roles')
        <a href="{{ route('admin.roles.index') }}"
            class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Jabatan
        </a>
        @endcan

        @can('admin_access_users')
        <a href="{{ route('admin.users.index') }}"
            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
            Users
        </a>
        @endcan
        @endcanany

    </aside>

    {{-- =================== MAIN CONTENT =================== --}}
    <main id="main-content" class="fade-up">

        @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert-error">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

</div>{{-- #layout --}}

<script>
    // ========= Sidebar toggle =========
    const sidebar      = document.getElementById('sidebar');
    const mainContent  = document.getElementById('main-content');
    const overlay      = document.getElementById('sidebar-overlay');
    const toggleBtn    = document.getElementById('sidebarToggleBtn');
    let sidebarOpen    = window.innerWidth >= 768;

    function updateLayout() {
        if (window.innerWidth < 768) {
            // Mobile: overlay mode
            sidebar.classList.toggle('open', sidebarOpen);
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('sidebar-collapsed');
            overlay.classList.toggle('show', sidebarOpen);
        } else {
            // Desktop: push mode
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            if (!sidebarOpen) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('sidebar-collapsed');
            }
        }
    }

    function closeSidebar() {
        sidebarOpen = false;
        updateLayout();
    }

    toggleBtn.addEventListener('click', () => {
        sidebarOpen = !sidebarOpen;
        updateLayout();
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && !sidebarOpen) sidebarOpen = true;
        updateLayout();
    });

    updateLayout();
</script>
@stack('scripts')
</body>
</html>
