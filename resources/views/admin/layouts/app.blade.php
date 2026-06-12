<!DOCTYPE html>
<html lang="id" class="dark">
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
            --sidebar-w:    260px;
            --app-bg: linear-gradient(160deg, #eef2ff 0%, #e0e7ff 50%, #dbeafe 100%);
            --glass-bg: rgba(238,242,255,0.92);
            --glass-border: rgba(99,102,241,0.12);
            --glass-shadow: 0 4px 24px rgba(99,102,241,0.08);
            --text-primary: #0f172a;
            --text-secondary: #1e3a5f;
            --text-muted: #5472a4;
            --nav-link-hover: rgba(99,102,241,0.06);
            --nav-link-active: rgba(99,102,241,0.1);
            --sidebar-bg: #1e40af;
            --card-bg: rgba(238,242,255,0.85);
            --card-border: rgba(99,102,241,0.12);
            --card-shadow: 0 4px 20px rgba(99,102,241,0.06);
            --dropdown-bg: rgba(238,242,255,0.98);
            --divider: #c7d2fe;
            --avatar-gradient: linear-gradient(135deg, #6366f1, #7c3aed);
            --scrollbar: rgba(99,102,241,0.2);
            --scrollbar-hover: rgba(99,102,241,0.35);
            --surface-bg: rgba(238,242,255,0.7);
            --input-bg: rgba(255,255,255,0.9);
            --input-border: rgba(99,102,241,0.15);
            --hover-bg: rgba(99,102,241,0.06);
            --accent: #6366f1;
            --navbar-bg: #1d4ed8;
            --navbar-text: #ffffff;
            --sidebar-text: #ffffff;
        }
        .dark {
            --app-bg: radial-gradient(ellipse 70% 50% at 0% 80%, rgba(99,102,241,0.12) 0%, transparent 60%),
                       radial-gradient(ellipse 60% 40% at 100% 20%, rgba(129,140,248,0.08) 0%, transparent 50%),
                       linear-gradient(160deg, #1e1b4b 0%, #312e81 50%, #1e1b4b 100%);
            --glass-bg: rgba(30,27,75,0.88);
            --glass-border: rgba(129,140,248,0.1);
            --glass-shadow: 0 4px 24px rgba(0,0,0,0.5);
            --text-primary: #e0e7ff;
            --text-secondary: #c7d2fe;
            --text-muted: #a5b4fc;
            --nav-link-hover: rgba(99,102,241,0.15);
            --nav-link-active: rgba(99,102,241,0.22);
            --sidebar-bg: #152a47;
            --card-bg: rgba(49,46,129,0.5);
            --card-border: rgba(129,140,248,0.12);
            --card-shadow: 0 4px 24px rgba(0,0,0,0.5);
            --dropdown-bg: rgba(30,27,75,0.96);
            --divider: rgba(129,140,248,0.1);
            --avatar-gradient: linear-gradient(135deg, #6366f1, #7c3aed);
            --scrollbar: rgba(99,102,241,0.3);
            --scrollbar-hover: rgba(99,102,241,0.5);
            --surface-bg: rgba(49,46,129,0.35);
            --input-bg: rgba(49,46,129,0.4);
            --input-border: rgba(129,140,248,0.15);
            --hover-bg: rgba(99,102,241,0.12);
            --accent: #818cf8;
            --navbar-bg: #1e3a5f;
            --navbar-text: #ffffff;
            --sidebar-text: #ffffff;
        }

        html, body { height: 100%; margin: 0; }

        body {
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===================== SCROLLBAR ===================== */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--scrollbar-hover); }

        /* ===================== GLASS COMPONENTS ===================== */
        .glass-nav {
            background: var(--navbar-bg);
            backdrop-filter: blur(20px) saturate(1.4);
            -webkit-backdrop-filter: blur(20px) saturate(1.4);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            color: var(--navbar-text);
        }
        .glass-sidebar {
            background: var(--sidebar-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-right: 1px solid var(--glass-border);
            color: var(--sidebar-text);
        }
        .glass-sidebar .nav-link { color: var(--sidebar-text); }
        .glass-sidebar .nav-link:hover {
            color: var(--sidebar-text);
            background: rgba(255,255,255,0.1);
        }
        .glass-sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2);
        }
        .glass-dropdown {
            background: var(--dropdown-bg);
            backdrop-filter: blur(24px) saturate(1.3);
            -webkit-backdrop-filter: blur(24px) saturate(1.3);
            border: 1px solid var(--glass-border);
            box-shadow: 0 16px 48px rgba(0,0,0,0.4);
        }

        /* ===================== APP BG ===================== */
        .app-bg { background: var(--app-bg); }

        /* ===================== TOPBAR ===================== */
        #topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
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
            letter-spacing: 0.02em;
        }
        .brand-sub {
            font-size: 10px;
            font-weight: 400;
            opacity: 0.75;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hamburger-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background .2s;
            display: flex;
            align-items: center;
        }
        .hamburger-btn:hover { background: rgba(255,255,255,0.1); }

        .theme-toggle-btn {
            transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .theme-toggle-btn:hover {
            transform: rotate(15deg);
        }

        .user-avatar-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 9999px;
            transition: background .2s;
        }
        .user-avatar-btn:hover { background: rgba(255,255,255,0.1); }
        .avatar-circle {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--avatar-gradient);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 12px rgba(37,99,235,0.3);
        }

        /* Dropdown items */
        .dropdown-item {
            display: flex; align-items: center; gap: 12px;
            padding: 9px 12px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none; background: none; width: 100%; text-align: left;
        }
        .dropdown-item:hover {
            background: var(--nav-link-hover);
            color: var(--text-primary);
            transform: translateX(3px);
        }
        .dropdown-item.danger { color: #f87171; }
        .dropdown-item.danger:hover { background: rgba(239,68,68,0.1); color: #ef4444; }
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
            display: flex;
            flex-direction: column;
            padding: 16px 12px;
            overflow-y: auto;
            position: fixed;
            top: 60px; left: 0; bottom: 0;
            transition: transform .3s cubic-bezier(.4,0,.2,1), width .3s, padding .3s;
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
            opacity: 0.7;
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
            border-radius: 14px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
            margin-bottom: 2px;
            cursor: pointer;
        }
        .nav-link:hover {
            background: var(--nav-link-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }
        .nav-link.active {
            background: var(--nav-link-active);
            color: var(--accent);
            box-shadow: inset 0 0 0 1px rgba(59,130,246,0.15);
        }
        .nav-link svg { flex-shrink: 0; transition: filter 0.3s; }
        .nav-link:hover svg { filter: drop-shadow(0 0 8px rgba(255,255,255,0.3)); }

        /* ===================== CARDS ===================== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: transform .25s, box-shadow .25s;
        }
        .card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.35); }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: var(--accent);
            color: #fff;
            border-radius: 10px;
            font-size: 14px; font-weight: 600;
            border: none; cursor: pointer;
            text-decoration: none;
            transition: background .2s, box-shadow .2s, transform .15s;
        }
        .btn-primary:hover {
            background: #1e40af;
            box-shadow: 0 6px 20px rgba(30,64,175,0.4);
            transform: translateY(-1px);
        }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: transparent;
            color: var(--text-primary);
            border-radius: 10px;
            font-size: 14px; font-weight: 500;
            border: 1px solid var(--card-border); cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-secondary:hover { background: var(--hover-bg); }

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
        tbody tr:hover td { background: var(--hover-bg); }

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
            backdrop-filter: blur(8px);
        }
        .alert-error {
            padding: 12px 16px; margin-bottom: 16px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            color: #f87171;
            display: flex; align-items: center; gap: 10px;
            font-size: 13px;
            backdrop-filter: blur(8px);
        }

        /* Input / Form */
        .input-field {
            width: 100%;
            padding: 9px 13px;
            border-radius: 9px;
            font-size: 13px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-primary);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-field:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(30,64,175,0.12);
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
        .page-header h1 { font-size: 20px; font-weight: 700; color: var(--text-primary); }
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .main-content { animation: fadeIn 0.4s ease-out; }

        /* Empty state */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: var(--text-muted);
        }
        .empty-state svg {
            width: 40px;
            height: 40px;
            margin: 0 auto 12px;
            display: block;
            color: var(--text-muted);
        }
        .empty-state p {
            font-size: 13px;
            margin: 0;
            color: var(--text-secondary);
        }
    </style>
</head>
<body class="app-bg text-[var(--text-primary)]">

{{-- =================== TOPBAR =================== --}}
<nav id="topbar" class="glass-nav">
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
        {{-- Theme Toggle --}}
        <button id="themeToggle" class="theme-toggle-btn p-2 hover:bg-white/10 rounded-full transition" title="Toggle tema">
            <svg id="themeIconSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="themeIconMoon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        {{-- Live Clock --}}
        <div class="hidden md:block text-sm font-medium px-2 opacity-80" id="headerDateTime"></div>

        {{-- User Dropdown --}}
        <div class="relative" style="position:relative" x-data="{ open: false }">
            <button @click="open = !open" @keydown.escape.window="open = false"
                class="user-avatar-btn focus:outline-none focus:ring-2 focus:ring-violet-400">
                <div class="avatar-circle">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </div>
            </button>

            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="open = false"
                class="glass-dropdown absolute right-0 top-full mt-2 w-64 rounded-2xl z-50 overflow-hidden">

                {{-- User Info Header --}}
                <div class="px-5 py-4 border-b border-[var(--divider)]">
                    <div class="flex items-center gap-3">
                        <div class="avatar-circle" style="width:44px;height:44px;font-size:16px;flex-shrink:0">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-[var(--text-primary)] text-sm truncate">{{ auth()->user()?->name }}</div>
                            <div class="text-xs text-[var(--text-muted)] truncate">{{ auth()->user()?->email }}</div>
                        </div>
                    </div>
                </div>

                {{-- Menu Items --}}
                <div class="py-2 px-2">
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <div class="w-8 h-8 rounded-full bg-[var(--nav-link-hover)] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Profil Saya</span>
                    </a>

                    <a href="{{ route('meeting.join.form') }}" class="dropdown-item">
                        <div class="w-8 h-8 rounded-full bg-[var(--nav-link-hover)] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </div>
                        <span class="font-medium">Ke Halaman User</span>
                    </a>

                    <div class="dd-sep"></div>

                    <a href="{{ route('logout') }}" class="dropdown-item danger">
                        <div class="w-8 h-8 rounded-full bg-red-500/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="font-medium">Keluar</span>
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
    <aside id="sidebar" class="glass-sidebar">

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
    <main id="main-content" class="main-content">

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

    // ── Theme management ──
    (function() {
        const html = document.documentElement;
        const sunIcon = document.getElementById('themeIconSun');
        const moonIcon = document.getElementById('themeIconMoon');
        const toggleBtn = document.getElementById('themeToggle');

        function setTheme(dark) {
            html.classList.toggle('dark', dark);
            if (sunIcon) sunIcon.classList.toggle('hidden', !dark);
            if (moonIcon) moonIcon.classList.toggle('hidden', dark);
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        }

        function initTheme() {
            const stored = localStorage.getItem('theme');
            if (stored) {
                setTheme(stored === 'dark');
            } else {
                setTheme(window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                setTheme(!html.classList.contains('dark'));
            });
        }

        initTheme();

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) setTheme(e.matches);
        });
    })();

    // ── Clock ──
    function updateTime() {
        const el = document.getElementById('headerDateTime');
        if (el) {
            const now = new Date();
            let h = now.getHours();
            const m = now.getMinutes().toString().padStart(2, '0');
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            el.innerText = h + ':' + m + ' ' + ampm + ' \u2022 ' + days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()];
        }
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>
@stack('scripts')
</body>
</html>
