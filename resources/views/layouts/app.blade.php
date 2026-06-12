<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Rapat Video</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }

        /* ── Theme variables ── */
        :root {
            --app-bg: linear-gradient(160deg, #f0f2f5 0%, #e8eaf0 50%, #e2e5ec 100%);
            --glass-bg: rgba(255,255,255,0.9);
            --glass-border: #e2e8f0;
            --glass-shadow: 0 4px 24px rgba(0,0,0,0.06);
            --text-primary: #0f172a;
            --text-secondary: #1e293b;
            --text-muted: #64748b;
            --nav-link-hover: rgba(139,92,246,0.08);
            --nav-link-active: rgba(139,92,246,0.12);
            --sidebar-bg: #1d4ed8;
            --sidebar-text: #ffffff;
            --card-bg: rgba(255,255,255,0.95);
            --card-border: #e2e8f0;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.06);
            --dropdown-bg: rgba(255,255,255,0.98);
            --divider: #e2e8f0;
            --avatar-gradient: linear-gradient(135deg, #7c3aed, #a78bfa);
            --scrollbar-thumb: rgba(139,92,246,0.2);
            --scrollbar-thumb-hover: rgba(139,92,246,0.35);
            --surface-bg: rgba(255,255,255,0.7);
            --input-bg: rgba(255,255,255,0.9);
            --input-border: #cbd5e1;
            --accent: #7c3aed;
            --hover-bg: rgba(0,0,0,0.05);
            --navbar-bg: #2563eb;
            --navbar-text: #ffffff;
        }
        .dark {
            --app-bg: radial-gradient(ellipse 70% 50% at 0% 80%, rgba(139,92,246,0.08) 0%, transparent 60%),
                       radial-gradient(ellipse 60% 40% at 100% 20%, rgba(6,182,212,0.06) 0%, transparent 50%),
                       linear-gradient(160deg, #0a0a0f 0%, #151520 50%, #0d0d12 100%);
            --glass-bg: rgba(15,15,25,0.88);
            --glass-border: rgba(255,255,255,0.06);
            --glass-shadow: 0 4px 24px rgba(0,0,0,0.3);
            --text-primary: #ffffff;
            --text-secondary: #e5e7eb;
            --text-muted: #d1d5db;
            --nav-link-hover: rgba(139,92,246,0.1);
            --nav-link-active: rgba(139,92,246,0.15);
            --sidebar-bg: #152a47;
            --sidebar-text: #ffffff;
            --card-bg: rgba(255,255,255,0.06);
            --card-border: rgba(255,255,255,0.06);
            --card-shadow: 0 8px 32px rgba(0,0,0,0.3);
            --dropdown-bg: rgba(20,20,30,0.96);
            --divider: rgba(255,255,255,0.05);
            --avatar-gradient: linear-gradient(135deg, #7c3aed, #a78bfa);
            --scrollbar-thumb: rgba(139,92,246,0.3);
            --scrollbar-thumb-hover: rgba(139,92,246,0.5);
            --surface-bg: rgba(255,255,255,0.03);
            --input-bg: rgba(255,255,255,0.05);
            --input-border: rgba(255,255,255,0.08);
            --accent: #a78bfa;
            --hover-bg: rgba(255,255,255,0.07);
            --navbar-bg: #1e3a5f;
            --navbar-text: #ffffff;
        }

        #sidebar { transition: transform 0.3s ease, width 0.3s ease, padding 0.3s ease; }
        #sidebar-overlay { transition: opacity 0.3s ease; }

        .app-bg { background: var(--app-bg); }
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
        .nav-link {
            color: var(--text-secondary);
            transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
            border-radius: 14px;
        }
        .nav-link:hover {
            background: var(--nav-link-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }
        .nav-link.active {
            background: var(--nav-link-active);
            color: #a78bfa;
            box-shadow: inset 0 0 0 1px rgba(139,92,246,0.15);
        }
        .nav-link svg { transition: filter 0.3s; }
        .nav-link:hover svg { filter: drop-shadow(0 0 8px rgba(139,92,246,0.3)); }
        .dropdown-item {
            color: var(--text-secondary);
            transition: all 0.2s;
            border-radius: 12px;
        }
        .dropdown-item:hover {
            background: var(--nav-link-hover);
            color: var(--text-primary);
            transform: translateX(3px);
        }
        .avatar-circle {
            background: var(--avatar-gradient);
            box-shadow: 0 2px 12px rgba(124,58,237,0.3);
        }
        .page-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
        }
        .page-card:hover {
            border-color: var(--accent, rgba(139,92,246,0.3));
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        }
        .surface-card {
            background: var(--surface-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 16px;
        }
        .input-theme {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-primary);
        }
        .input-theme:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
        }
        .theme-toggle-btn {
            transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .theme-toggle-btn:hover {
            transform: rotate(15deg);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--scrollbar-thumb-hover); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .main-content { animation: fadeIn 0.4s ease-out; }
    </style>
</head>

<body class="app-bg min-h-screen flex flex-col text-[var(--text-primary)] overflow-hidden">

    <div x-data="{
        sidebarOpen: window.innerWidth >= 768,
        init() {
            this.$watch('sidebarOpen', v => {});
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) this.sidebarOpen = true;
            });
        }
    }">

        <!-- ======================== -->
        <!-- NAVBAR                  -->
        <!-- ======================== -->
        <nav class="glass-nav px-4 py-3 flex justify-between items-center w-full z-30 relative">
            <!-- Left: Hamburger + Logo + App Name -->
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 hover:bg-[var(--nav-link-hover)] rounded-full transition focus:outline-none"
                    aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('meeting.join.form') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                    <span class="text-lg font-semibold tracking-tight hidden sm:block">
                        {{ config('app.name', 'MEET BPS') }}
                    </span>
                </a>
            </div>

            <!-- Right: Theme Toggle + Clock + Profile Dropdown -->
            <div class="flex items-center gap-2 md:gap-4">
                <button id="themeToggle" class="theme-toggle-btn p-2 hover:bg-white/10 rounded-full transition" title="Toggle tema">
                    <svg id="themeIconSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg id="themeIconMoon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <div class="hidden md:block text-sm font-medium px-2 opacity-80" id="headerDateTime"></div>

                <!-- Avatar Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @keydown.escape.window="open = false"
                        class="flex items-center gap-2 p-1 rounded-full hover:bg-[var(--nav-link-hover)] transition focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <div class="w-9 h-9 rounded-full avatar-circle text-white flex items-center justify-center font-bold text-sm select-none">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                        </div>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @click.outside="open = false" style="display:none;"
                        class="absolute right-0 top-full mt-2 w-64 glass-dropdown rounded-2xl z-50 overflow-hidden">

                        <!-- User Info Header -->
                        <div class="px-5 py-4 border-b" style="border-color:var(--divider)">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full avatar-circle text-white flex items-center justify-center font-bold text-base flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-[var(--text-primary)] text-sm truncate">{{ auth()->user()?->name }}</p>
                                    <p class="text-xs text-[var(--text-muted)] truncate">{{ auth()->user()?->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2 px-2">
                            <a href="{{ route('profile.show') }}"
                                class="dropdown-item flex items-center gap-3 px-3 py-2.5 text-sm group">
                                <div class="w-8 h-8 rounded-full bg-[var(--nav-link-hover)] flex items-center justify-center flex-shrink-0 group-hover:bg-violet-500/20 transition">
                                    <svg class="w-4 h-4 text-[var(--text-secondary)] group-hover:text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="font-medium">Profil Saya</span>
                            </a>

                            @if (auth()->user()?->hasAnyRole(['super_admin', 'admin']))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="dropdown-item flex items-center gap-3 px-3 py-2.5 text-sm group">
                                    <div class="w-8 h-8 rounded-full bg-[var(--nav-link-hover)] flex items-center justify-center flex-shrink-0 group-hover:bg-violet-500/20 transition">
                                        <svg class="w-4 h-4 text-[var(--text-secondary)] group-hover:text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Halaman Admin</span>
                                </a>
                            @endif

                            <div class="border-t my-2 mx-1" style="border-color:var(--divider)"></div>

                            <a href="{{ route('logout') }}"
                                class="dropdown-item flex items-center gap-3 px-3 py-2.5 text-sm text-red-400 group">
                                <div class="w-8 h-8 rounded-full bg-red-500/10 flex items-center justify-center flex-shrink-0 group-hover:bg-red-500/20 transition">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span class="font-medium">Keluar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ======================== -->
        <!-- CONTENT + SIDEBAR       -->
        <!-- ======================== -->
        <main class="flex flex-1 w-full overflow-hidden" style="height: calc(100vh - 57px);">

            <!-- Overlay for mobile -->
            <div id="sidebar-overlay" x-show="sidebarOpen && $el.closest('body').offsetWidth < 768"
                @click="sidebarOpen = false" style="display:none;" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-20 md:hidden">
            </div>

            <!-- Sidebar -->
            <aside id="sidebar"
                :class="sidebarOpen ? 'translate-x-0 md:w-[260px] md:min-w-[260px] md:px-3 md:py-3' :
                    '-translate-x-full md:w-0 md:min-w-0 md:p-0 md:border-r-0 md:overflow-hidden'"
                class="glass-sidebar fixed md:relative z-30 md:z-auto top-0 left-0 md:left-auto w-[260px] h-screen md:h-full flex flex-col py-3 px-3 overflow-y-auto">

                <!-- Close button (mobile only) -->
                <div class="flex items-center justify-between mb-2 md:hidden">
                    <span class="text-sm font-semibold opacity-70 px-2">Menu</span>
                    <button @click="sidebarOpen = false" class="p-1.5 hover:bg-white/10 rounded-full opacity-70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @can('join_meeting')
                <a href="{{ route('meeting.join.form') }}"
                    class="nav-link flex items-center gap-3 font-medium py-2.5 px-4 mb-0.5 transition {{ request()->routeIs('meeting.join.form') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Beranda Rapat
                </a>
                @endcan
                @can('access_user_agenda')
                <a href="{{ route('meeting.agenda') }}"
                    class="nav-link flex items-center gap-3 font-medium py-2.5 px-4 mb-0.5 transition {{ request()->routeIs('meeting.agenda') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Agenda Rapat
                </a>
                @endcan
                @can('manage_meeting_recording')
                <a href="{{ route('video.index') }}"
                    class="nav-link flex items-center gap-3 font-medium py-2.5 px-4 mb-0.5 transition {{ request()->routeIs('video.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Rekaman Video
                </a>
                @endcan
                @can('access_user_audio')
                <a href="{{ route('audio.index') }}"
                    class="nav-link flex items-center gap-3 font-medium py-2.5 px-4 mb-0.5 transition {{ request()->routeIs('audio.index') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                    Audio Notulensi
                </a>
                <a href="{{ route('audio.history') }}"
                    class="nav-link flex items-center gap-3 font-medium py-2.5 px-4 mb-0.5 transition {{ request()->routeIs('audio.history') || request()->routeIs('audio.show') || request()->routeIs('audio.edit') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Notulensi
                </a>
                @endcan
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col h-full overflow-y-auto min-w-0 main-content">
                @yield('content')
            </div>
        </main>

    </div>{{-- end x-data --}}

    <script>
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

            // Listen for system changes
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
</body>

</html>
