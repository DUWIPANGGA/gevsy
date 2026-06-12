@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

    {{-- =================== HERO BANNER =================== --}}
    <div class="hero-banner">
        {{-- Decorative stars/sparkles --}}
        <div class="hero-sparkles">
            <div class="h-s-1"></div>
            <div class="h-s-2"></div>
            <div class="h-s-3"></div>
            <div class="h-s-4"></div>
            <div class="h-ring-1"></div>
            <div class="h-ring-2"></div>
            <div class="h-arc"></div>
        </div>

        {{-- Left: Text content --}}
        <div class="hero-text">
            <div class="hero-badge">
                <div class="h-badge-dot"></div>
                <span class="h-badge-label">Selamat Datang</span>
            </div>

            <h1 class="hero-title">
                Siap untuk rapat yang<br>
                lebih <span class="h-title-accent">produktif</span> hari ini? 🚀
            </h1>

            <p class="hero-desc">
                Kelola meeting dengan mudah, dokumentasi otomatis,<br>
                dan simpan setiap momen penting.
            </p>
            <p class="hero-tagline">
                Bersama BPS, Data Berkualitas, Indonesia Maju! ✨
            </p>

            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
                <a href="{{ route('admin.agendas.index') }}" class="hero-btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10" fill="rgba(255,255,255,0.2)" />
                        <polygon points="10,8 16,12 10,16" fill="white" />
                    </svg>
                    Mulai Aktivitas
                </a>

            </div>
        </div>

        {{-- RIGHT: Neon backdrop + mascot image + floating icons (all inside one container) --}}
        <div style="position:absolute;right:0;bottom:0;top:0;width:52%;z-index:1;overflow:visible;">

            {{-- ── Neon backdrop layers (z-index 0, behind image) ── --}}

            {{-- Outer corona - wide soft blue halo --}}
            <div
                style="position:absolute;bottom:-10%;left:50%;transform:translateX(-50%);
                width:420px;height:420px;border-radius:50%;
                background:radial-gradient(circle,rgba(29,78,216,0.55) 0%,rgba(37,99,235,0.30) 30%,rgba(56,189,248,0.12) 55%,transparent 75%);
                filter:blur(18px);z-index:0;animation:neonPulse 3.5s ease-in-out infinite;">
            </div>

            {{-- Core halo - tighter bright center --}}
            {{-- <div
                style="position:absolute;bottom:5%;left:50%;transform:translateX(-50%);
                width:220px;height:220px;border-radius:50%;
                background:radial-gradient(circle,rgba(96,165,250,0.75) 0%,rgba(59,130,246,0.45) 35%,transparent 70%);
                filter:blur(10px);z-index:0;animation:neonPulse 2.8s 0.4s ease-in-out infinite;">
            </div> --}}

            {{-- Upper rim light - cyan accent top --}}
            <div
                style="position:absolute;top:5%;left:50%;transform:translateX(-50%);
                width:160px;height:160px;border-radius:50%;
                background:radial-gradient(circle,rgba(34,211,238,0.35) 0%,rgba(56,189,248,0.15) 40%,transparent 70%);
                filter:blur(14px);z-index:0;animation:neonPulse 4s 1s ease-in-out infinite;">
            </div>

            {{-- Glowing ring circle --}}
            {{-- <div
                style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);
                width:280px;height:280px;border-radius:50%;
                border:2px solid rgba(56,189,248,0.25);
                box-shadow:0 0 20px rgba(56,189,248,0.3),inset 0 0 30px rgba(56,189,248,0.1);
                z-index:0;animation:ringPulse 3s ease-in-out infinite;">
            </div> --}}

            {{-- Ground reflection pool --}}
            <div
                style="position:absolute;bottom:-8px;left:80%;transform:translateX(-50%);
                width:300px;height:28px;border-radius:50%;
                background:radial-gradient(ellipse,rgba(56,189,248,0.5) 0%,rgba(37,99,235,0.25) 40%,transparent 70%);
                filter:blur(8px);z-index:0;">
            </div>

            {{-- ── Mascot image (z-index 2, above neon) ── --}}
            <div
                style="position:absolute;inset:0;overflow:hidden;display:flex;align-items:flex-end;justify-content:flex-end;z-index:2;">
                <img src="{{ asset('images/dashboard1.png') }}" alt="BPS Mascot"
                    style="height:100%;width:auto;max-height:500px;object-fit:cover;object-position:70% center;transform:translateX(8%);display:block;
                           filter:drop-shadow(0 0 28px rgba(56,130,246,0.6)) drop-shadow(0 0 8px rgba(56,189,248,0.4));">
            </div>

            {{-- ── Floating Icon: Video Camera (kiri atas) ── --}}
            <div style="position:absolute;top:10%;left:50%;z-index:3;animation:floatA 3.2s ease-in-out infinite;">
                <div class="float-icon" style="transform:perspective(-500px) rotateY(18deg) rotateX(-12deg);">
                    <div class="glass-icon-box gib-video">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="#7dd3fc" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14" />
                            <rect x="3" y="8" width="12" height="8" rx="2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ── Floating Icon: Microphone (kanan atas) ── --}}
            <div style="position:absolute;top:6%;right:5%;z-index:3;animation:floatB 3.8s 0.6s ease-in-out infinite;">
                <div class="float-icon" style="transform:perspective(500px) rotateY(-16deg) rotateX(-10deg);">
                    <div class="glass-icon-box gib-mic">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="#c4b5fd" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" />
                            <path d="M19 10v2a7 7 0 01-14 0v-2" />
                            <line x1="12" y1="19" x2="12" y2="23" />
                            <line x1="8" y1="23" x2="16" y2="23" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ── Floating Icon: Document (kanan tengah) ── --}}
            <div style="position:absolute;top:38%;right:2%;z-index:3;animation:floatC 4.1s 1.2s ease-in-out infinite;">
                <div class="float-icon" style="transform:perspective(500px) rotateY(-20deg) rotateX(8deg);">
                    <div class="glass-icon-box gib-doc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="#93c5fd" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ── Sparkle stars ── --}}
            <div style="position:absolute;top:22%;left:28%;z-index:3;animation:floatA 2.4s 0.3s ease-in-out infinite;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#38bdf8"
                        opacity="0.9" filter="url(#glow-star)" />
                    <defs>
                        <filter id="glow-star" x="-50%" y="-50%" width="200%" height="200%">
                            <feGaussianBlur stdDeviation="2" result="blur" />
                            <feMerge>
                                <feMergeNode in="blur" />
                                <feMergeNode in="SourceGraphic" />
                            </feMerge>
                        </filter>
                    </defs>
                </svg>
            </div>

            <div style="position:absolute;top:65%;left:15%;z-index:3;animation:floatB 3s 0.8s ease-in-out infinite;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#f0abfc"
                        opacity="0.85" />
                </svg>
            </div>

            <div style="position:absolute;top:55%;right:0%;z-index:3;animation:floatC 2.7s 1.5s ease-in-out infinite;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#fde68a"
                        opacity="0.9" />
                </svg>
            </div>

            {{-- Glowing orb blob --}}
            <div style="position:absolute;top:30%;left:80%;z-index:3;animation:floatB 3.5s 0.5s ease-in-out infinite;">
                <div
                    style="width:24px;height:24px;border-radius:50%;background:radial-gradient(circle,rgba(56,189,248,0.95) 0%,transparent 70%);box-shadow:0 0 16px 5px rgba(56,189,248,0.4);filter:blur(1px);">
                </div>
            </div>

            {{-- ── Additional floating stars around hero image ── --}}
            <div class="sparkle"
                style="position:absolute;top:12%;left:18%;z-index:3;width:5px;height:5px;background:#f0abfc;border-radius:50%;box-shadow:0 0 10px 3px rgba(240,171,252,.5);animation:twinkle 2.4s 0.1s infinite alternate;">
            </div>
            <div class="sparkle"
                style="position:absolute;top:70%;left:10%;z-index:3;width:3px;height:3px;background:#67e8f9;border-radius:50%;box-shadow:0 0 7px 2px rgba(103,232,249,.4);animation:twinkle 1.8s 0.6s infinite alternate;">
            </div>
            <div class="sparkle"
                style="position:absolute;top:45%;left:85%;z-index:3;width:4px;height:4px;background:#a78bfa;border-radius:50%;box-shadow:0 0 8px 3px rgba(167,139,250,.4);animation:twinkle 2.6s 1.1s infinite alternate;">
            </div>
            <div class="sparkle"
                style="position:absolute;top:8%;right:8%;z-index:3;width:3px;height:3px;background:#38bdf8;border-radius:50%;box-shadow:0 0 6px 2px rgba(56,189,248,.4);animation:twinkle 2s 0.3s infinite alternate;">
            </div>
            <div class="sparkle"
                style="position:absolute;top:78%;right:12%;z-index:3;width:5px;height:5px;background:#fde68a;border-radius:50%;box-shadow:0 0 9px 3px rgba(253,230,138,.4);animation:twinkle 2.2s 0.9s infinite alternate;">
            </div>

            <div style="position:absolute;top:18%;left:35%;z-index:3;animation:floatC 2.6s 0.2s ease-in-out infinite;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#c4b5fd"
                        opacity="0.85" />
                </svg>
            </div>
            <div style="position:absolute;top:50%;left:5%;z-index:3;animation:floatA 3.1s 0.7s ease-in-out infinite;">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#7dd3fc"
                        opacity="0.8" />
                </svg>
            </div>
            <div style="position:absolute;top:72%;right:6%;z-index:3;animation:floatB 2.9s 1.3s ease-in-out infinite;">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#f9a8d4"
                        opacity="0.85" />
                </svg>
            </div>
            <div style="position:absolute;top:35%;right:22%;z-index:3;animation:floatC 3.4s 0.4s ease-in-out infinite;">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2 L13.5 10.5 L22 12 L13.5 13.5 L12 22 L10.5 13.5 L2 12 L10.5 10.5 Z" fill="#93c5fd"
                        opacity="0.9" />
                </svg>
            </div>

            {{-- ══ 4 NEBULA ORBIT RINGS — semua di BELAKANG gambar, center di karakter ══ --}}
            <div
                style="position:absolute;
                        bottom:-40px;
                        left:80%;
                        transform:translateX(-50%);
                        width:380px;height:380px;
                        pointer-events:none;
                        z-index:1;">
                <svg viewBox="0 0 380 380" fill="none" width="380" height="380"
                    style="overflow:visible;position:absolute;top:0;left:0;">
                    <defs>
                        {{-- Gradient 1: Cyan → Indigo → Ungu --}}
                        <linearGradient id="ng1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#38bdf8" stop-opacity="0" />
                            <stop offset="25%" stop-color="#38bdf8" stop-opacity="1" />
                            <stop offset="60%" stop-color="#818cf8" stop-opacity="1" />
                            <stop offset="100%" stop-color="#a855f7" stop-opacity="0" />
                        </linearGradient>
                        {{-- Gradient 2: Violet → Pink Neon --}}
                        <linearGradient id="ng2" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#7c3aed" stop-opacity="0" />
                            <stop offset="30%" stop-color="#a855f7" stop-opacity="1" />
                            <stop offset="68%" stop-color="#ec4899" stop-opacity="0.95" />
                            <stop offset="100%" stop-color="#f472b6" stop-opacity="0" />
                        </linearGradient>
                        {{-- Gradient 3: Navy → Biru → Cyan --}}
                        <linearGradient id="ng3" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#1d4ed8" stop-opacity="0" />
                            <stop offset="38%" stop-color="#3b82f6" stop-opacity="1" />
                            <stop offset="72%" stop-color="#22d3ee" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#38bdf8" stop-opacity="0" />
                        </linearGradient>
                        {{-- Gradient 4: Violet gelap → Biru langit --}}
                        <linearGradient id="ng4" x1="0%" y1="100%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#6d28d9" stop-opacity="0" />
                            <stop offset="42%" stop-color="#8b5cf6" stop-opacity="1" />
                            <stop offset="78%" stop-color="#60a5fa" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#38bdf8" stop-opacity="0" />
                        </linearGradient>

                        {{-- Glow filter: blur + screen blend untuk efek nebula --}}
                        <filter id="nebulaGlow" x="-35%" y="-35%" width="170%" height="170%">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="4.5" result="blur" />
                            <feBlend in="SourceGraphic" in2="blur" mode="screen" />
                        </filter>
                    </defs>

                    {{-- Ring 1 — Cyan→Ungu, miring lembut, lambat (7s) --}}
                    <ellipse cx="190" cy="190" rx="172" ry="60" stroke="url(#ng1)"
                        stroke-width="3" fill="none" filter="url(#nebulaGlow)"
                        style="transform-origin:190px 190px;
                               transform:rotateX(68deg);
                               animation:orbitSpin1 7s linear infinite;" />

                    {{-- Ring 2 — Violet→Pink, berlawanan arah, sedang (10s) --}}
                    <ellipse cx="190" cy="190" rx="160" ry="52" stroke="url(#ng2)"
                        stroke-width="2.5" fill="none" filter="url(#nebulaGlow)"
                        style="transform-origin:190px 190px;
                               transform:rotateX(68deg) rotateZ(55deg);
                               animation:orbitSpin2 10s linear infinite;opacity:0.9;" />

                    {{-- Ring 3 — Biru→Cyan, hampir datar, cepat (6s) --}}
                    <ellipse cx="190" cy="190" rx="175" ry="42" stroke="url(#ng3)"
                        stroke-width="2.5" fill="none" filter="url(#nebulaGlow)"
                        style="transform-origin:190px 190px;
                               transform:rotateX(75deg) rotateZ(110deg);
                               animation:orbitSpin3 6s linear infinite;opacity:0.85;" />

                    {{-- Ring 4 — Violet→Biru langit, tegak, paling lambat (14s) --}}
                    <ellipse cx="190" cy="190" rx="150" ry="68" stroke="url(#ng4)"
                        stroke-width="2" fill="none" filter="url(#nebulaGlow)"
                        style="transform-origin:190px 190px;
                               transform:rotateX(62deg) rotateZ(165deg);
                               animation:orbitSpin4 14s linear infinite;opacity:0.8;" />
                </svg>
            </div>

        </div>{{-- end right container --}}
    </div>{{-- end hero banner --}}

    {{-- =================== ACTIVITY SUMMARY =================== --}}
    <h2 style="font-size:16px;font-weight:700;color:var(--text-primary);margin:0 0 16px;letter-spacing:-0.01em">
        Ringkasan Aktivitas
    </h2>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px" class="stats-grid">

        {{-- Total Meeting --}}
        <div class="stat-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div class="stat-icon" style="background:linear-gradient(135deg,#1e40af22,#3b82f622)">
                    <svg width="20" height="20" fill="none" stroke="#60a5fa" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div
                style="font-size:12px;color:var(--text-secondary);font-weight:500;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.05em">
                Total Meeting</div>
            <div style="font-size:32px;font-weight:800;color:var(--text-primary);letter-spacing:-0.02em;line-height:1">
                {{ $stats['total_meetings'] }}</div>
            <div class="stat-wave" style="border-color:#3b82f6;margin-top:12px">
                <svg viewBox="0 0 80 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="80"
                    height="20">
                    <path d="M0 10 Q10 4 20 10 Q30 16 40 10 Q50 4 60 10 Q70 16 80 10" stroke="#3b82f6" stroke-width="1.8"
                        fill="none" opacity="0.7" />
                </svg>

            </div>
        </div>

        {{-- Rekaman Audio --}}
        <div class="stat-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div class="stat-icon" style="background:linear-gradient(135deg,#4c1d9522,#8b5cf622)">
                    <svg width="20" height="20" fill="none" stroke="#a78bfa" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                </div>
            </div>
            <div
                style="font-size:12px;color:var(--text-secondary);font-weight:500;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.05em">
                Rekaman Audio</div>
            <div style="font-size:32px;font-weight:800;color:var(--text-primary);letter-spacing:-0.02em;line-height:1">
                {{ $stats['total_rekaman'] }}</div>
            <div class="stat-wave" style="margin-top:12px">
                <svg viewBox="0 0 80 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="80"
                    height="20">
                    <path d="M0 10 Q10 4 20 10 Q30 16 40 10 Q50 4 60 10 Q70 16 80 10" stroke="#a78bfa" stroke-width="1.8"
                        fill="none" opacity="0.7" />
                </svg>

            </div>
        </div>

        {{-- Transkripsi --}}
        <div class="stat-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div class="stat-icon" style="background:linear-gradient(135deg,#06403022,#0d948022)">
                    <svg width="20" height="20" fill="none" stroke="#2dd4bf" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            </div>
            <div
                style="font-size:12px;color:var(--text-secondary);font-weight:500;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.05em">
                Transkripsi</div>
            <div style="font-size:32px;font-weight:800;color:var(--text-primary);letter-spacing:-0.02em;line-height:1">
                {{ $stats['total_transkripsi'] }}</div>
            <div class="stat-wave" style="margin-top:12px">
                <svg viewBox="0 0 80 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="80"
                    height="20">
                    <path d="M0 10 Q10 4 20 10 Q30 16 40 10 Q50 4 60 10 Q70 16 80 10" stroke="#2dd4bf" stroke-width="1.8"
                        fill="none" opacity="0.7" />
                </svg>

            </div>
        </div>

        {{-- Notulensi --}}
        <div class="stat-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div class="stat-icon" style="background:linear-gradient(135deg,#78350f22,#f59e0b22)">
                    <svg width="20" height="20" fill="none" stroke="#fbbf24" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div
                style="font-size:12px;color:var(--text-secondary);font-weight:500;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.05em">
                Notulensi</div>
            <div style="font-size:32px;font-weight:800;color:var(--text-primary);letter-spacing:-0.02em;line-height:1">
                {{ $stats['total_notulensi'] }}</div>
            <div class="stat-wave" style="margin-top:12px">
                <svg viewBox="0 0 80 20" fill="none" xmlns="http://www.w3.org/2000/svg" width="80"
                    height="20">
                    <path d="M0 10 Q10 4 20 10 Q30 16 40 10 Q50 4 60 10 Q70 16 80 10" stroke="#fbbf24" stroke-width="1.8"
                        fill="none" opacity="0.7" />
                </svg>

            </div>
        </div>

    </div>

    {{-- =================== RECENT MEETINGS TABLE =================== --}}
    <div class="card" style="overflow:hidden">
        <div
            style="display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--divider)">
            <h3 style="font-size:14px;font-weight:700;color:var(--text-primary);margin:0">Rapat Terbaru</h3>
            <a href="{{ route('admin.meetings.index') }}"
                style="font-size:12px;color:#3b82f6;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:4px;transition:opacity .2s"
                onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                Lihat Semua
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if ($recentMeetings->count())
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentMeetings as $m)
                        <tr>
                            <td style="font-weight:600;color:var(--text-primary)">{{ $m->nama_rapat }}</td>
                            <td>
                                <span class="badge"
                                    style="
                        background:{{ $m->tipe_rapat === 'Online' ? 'rgba(59,130,246,0.12)' : 'rgba(245,158,11,0.12)' }};
                        color:{{ $m->tipe_rapat === 'Online' ? '#2563eb' : '#d97706' }};
                    ">{{ $m->tipe_rapat }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }}
                                {{ $m->waktu ? '· ' . substr($m->waktu, 0, 5) : '' }}</td>
                            <td>
                                <span class="badge"
                                    style="
                        background:{{ $m->status_rapat === 'Berlangsung' ? 'rgba(16,185,129,0.12)' : 'rgba(99,102,241,0.12)' }};
                        color:{{ $m->status_rapat === 'Berlangsung' ? '#059669' : '#4f46e5' }};
                    ">{{ $m->status_rapat }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:48px;text-align:center">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24" style="margin:0 auto 12px;color:var(--text-muted)">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p style="color:var(--text-secondary);font-size:13px">Belum ada rapat yang tercatat.</p>
            </div>
        @endif
    </div>

    <style>
        /* =================== HERO BANNER =================== */
        .hero-banner {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            margin-bottom: 28px;
            min-height: 240px;
            display: flex;
            align-items: center;
            background: radial-gradient(ellipse 80% 100% at 70% 50%, #1e40af 0%, #1e3a8a 60%),
                        linear-gradient(135deg, #172554 0%, #1e3a8a 50%, #3b82f6 100%);
        }
        :root:not(.dark) .hero-banner {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 30%, #7dd3fc 60%, #38bdf8 100%);
        }
        :root:not(.dark) .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.9);
            border-radius: 99px;
            padding: 5px 14px;
            margin-bottom: 16px;
        }
        :root:not(.dark) .h-badge-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #0284c7;
            box-shadow: 0 0 6px #0284c7;
        }
        :root:not(.dark) .h-badge-label {
            font-size: 12px;
            font-weight: 600;
            color: #0369a1;
            letter-spacing: 0.04em;
        }
        :root:not(.dark) .hero-title {
            font-size: clamp(22px,3vw,30px);
            font-weight: 800;
            color: #0c4a6e;
            line-height: 1.25;
            margin: 0 0 12px;
            letter-spacing: -0.02em;
        }
        :root:not(.dark) .h-title-accent { color: #0369a1; }
        :root:not(.dark) .hero-desc {
            font-size: 13px;
            color: #0c4a6e;
            line-height: 1.65;
            margin: 0 0 8px;
            opacity: 0.8;
        }
        :root:not(.dark) .hero-tagline {
            font-size: 12px;
            font-weight: 600;
            color: #0c4a6e;
            margin: 0 0 24px;
            opacity: 0.7;
        }

        .dark .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 99px;
            padding: 5px 14px;
            margin-bottom: 16px;
        }
        .dark .h-badge-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #38bdf8;
            box-shadow: 0 0 6px #38bdf8;
        }
        .dark .h-badge-label {
            font-size: 12px;
            font-weight: 600;
            color: #e0f2fe;
            letter-spacing: 0.04em;
        }
        .dark .hero-title {
            font-size: clamp(22px,3vw,30px);
            font-weight: 800;
            color: #fff;
            line-height: 1.25;
            margin: 0 0 12px;
            letter-spacing: -0.02em;
        }
        .dark .h-title-accent { color: #38bdf8; }
        .dark .hero-desc {
            font-size: 13px;
            color: #94b3cc;
            line-height: 1.65;
            margin: 0 0 8px;
        }
        .dark .hero-tagline {
            font-size: 12px;
            font-weight: 600;
            color: #c0d9f0;
            margin: 0 0 24px;
        }

        .hero-text {
            flex: 1;
            padding: 36px 40px;
            position: relative;
            z-index: 2;
            max-width: 55%;
        }

        .hero-sparkles { position:absolute;inset:0;pointer-events:none;overflow:hidden; }

        .h-s-1 { top:15%;left:12%;width:6px;height:6px;background:#38bdf8;box-shadow:0 0 10px 4px rgba(56,189,248,.5);animation:twinkle 2.1s infinite alternate; }
        .h-s-2 { top:60%;left:8%;width:4px;height:4px;background:#818cf8;box-shadow:0 0 8px 3px rgba(129,140,248,.5);animation:twinkle 2.8s 0.4s infinite alternate; }
        .h-s-3 { top:30%;left:40%;width:5px;height:5px;background:#f0abfc;box-shadow:0 0 8px 3px rgba(240,171,252,.4);animation:twinkle 1.9s 0.8s infinite alternate; }
        .h-s-4 { top:75%;left:30%;width:4px;height:4px;background:#38bdf8;box-shadow:0 0 7px 3px rgba(56,189,248,.4);animation:twinkle 2.5s 0.2s infinite alternate; }
        .h-ring-1 { position:absolute;bottom:-40px;left:80%;width:180px;height:180px;border:1.5px solid rgba(56,189,248,0.18);border-radius:50%;animation:pulse-ring 3s ease-in-out infinite; }
        .h-ring-2 { position:absolute;bottom:-60px;left:calc(80% - 30px);width:240px;height:240px;border:1.5px solid rgba(99,102,241,0.13);border-radius:50%;animation:pulse-ring 4s 1s ease-in-out infinite; }
        .h-arc { position:absolute;top:50%;right:0;width:55%;height:120%;transform:translateY(-50%);background:radial-gradient(ellipse 70% 80% at 90% 50%,rgba(56,189,248,0.07) 0%,transparent 70%);pointer-events:none; }

        :root:not(.dark) .h-s-1, :root:not(.dark) .h-s-4 { background:#0284c7; box-shadow:0 0 10px 4px rgba(2,132,199,.4); }
        :root:not(.dark) .h-s-2 { background:#6366f1; box-shadow:0 0 8px 3px rgba(99,102,241,.4); }
        :root:not(.dark) .h-s-3 { background:#d946ef; box-shadow:0 0 8px 3px rgba(217,70,239,.3); }
        :root:not(.dark) .h-ring-1 { border-color:rgba(2,132,199,0.2); }
        :root:not(.dark) .h-ring-2 { border-color:rgba(99,102,241,0.15); }
        :root:not(.dark) .h-arc { background:radial-gradient(ellipse 70% 80% at 90% 50%,rgba(2,132,199,0.08) 0%,transparent 70%); }

        /* Hero button */
        .hero-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            background: #2563eb;
            color: #fff;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background .2s, box-shadow .2s, transform .15s;
            box-shadow: 0 4px 18px rgba(37, 99, 235, 0.45);
        }

        .hero-btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(37, 99, 235, 0.55);
        }

        /* Stat cards */
        .stats-grid {}

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
            }

            .bottom-row {
                grid-template-columns: 1fr !important;
            }
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 20px;
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.2);
            border-color: var(--accent);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-wave {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Info cards */
        .info-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 22px;
            transition: transform .25s, box-shadow .25s;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.15);
        }

        /* Sparkle animation */
        @keyframes twinkle {
            from {
                opacity: 0.2;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* ── Neon backdrop animations ── */
        @keyframes neonPulse {

            0%,
            100% {
                opacity: 0.75;
                transform: translateX(-50%) scale(1);
                filter: blur(18px) brightness(1);
            }

            50% {
                opacity: 1;
                transform: translateX(-50%) scale(1.08);
                filter: blur(14px) brightness(1.3);
            }
        }

        @keyframes ringPulse {

            0%,
            100% {
                opacity: 0.5;
                box-shadow: 0 0 16px rgba(56, 189, 248, 0.2), inset 0 0 20px rgba(56, 189, 248, 0.07);
                transform: translateX(-50%) scale(1);
            }

            50% {
                opacity: 0.9;
                box-shadow: 0 0 36px rgba(56, 189, 248, 0.55), inset 0 0 50px rgba(56, 189, 248, 0.2);
                transform: translateX(-50%) scale(1.04);
            }
        }

        @keyframes pulse-ring {

            0%,
            100% {
                opacity: 0.15;
                transform: scale(1);
            }

            50% {
                opacity: 0.3;
                transform: scale(1.04);
            }
        }

        /* ── Floating icon glass boxes — base ── */
        .glass-icon-box {
            width: 62px;
            height: 62px;
            border-radius: 20px;
            background: linear-gradient(145deg, rgba(10, 35, 100, 0.7) 0%, rgba(20, 55, 140, 0.55) 100%);
            backdrop-filter: blur(16px) saturate(1.6);
            -webkit-backdrop-filter: blur(16px) saturate(1.6);
            border: 1.5px solid rgba(125, 211, 252, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform .25s, box-shadow .25s;
            /* Base neon glow */
            box-shadow:
                0 0 0 1px rgba(125, 211, 252, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.18),
                inset 0 -1px 0 rgba(0, 0, 0, 0.3),
                0 8px 32px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(56, 189, 248, 0.35),
                0 0 40px rgba(56, 189, 248, 0.15);
            animation: glowPulse 2.8s ease-in-out infinite;
        }

        /* Glass shine overlay */
        .glass-icon-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 55%;
            border-radius: 20px 20px 0 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0) 100%);
            pointer-events: none;
        }

        /* Bottom edge highlight */
        .glass-icon-box::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            right: 10%;
            height: 2px;
            border-radius: 0 0 20px 20px;
            background: linear-gradient(90deg, transparent, rgba(125, 211, 252, 0.6), transparent);
            pointer-events: none;
        }

        /* Per-icon glow color variants */
        .gib-video {
            border-color: rgba(56, 189, 248, 0.55);
            animation-delay: 0s;
            box-shadow:
                0 0 0 1px rgba(56, 189, 248, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.18),
                inset 0 -1px 0 rgba(0, 0, 0, 0.3),
                0 8px 32px rgba(0, 0, 0, 0.5),
                0 0 22px rgba(56, 189, 248, 0.5),
                0 0 50px rgba(56, 189, 248, 0.25);
        }

        .gib-mic {
            border-color: rgba(167, 139, 250, 0.55);
            animation-delay: 0.9s;
            box-shadow:
                0 0 0 1px rgba(167, 139, 250, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.18),
                inset 0 -1px 0 rgba(0, 0, 0, 0.3),
                0 8px 32px rgba(0, 0, 0, 0.5),
                0 0 22px rgba(139, 92, 246, 0.55),
                0 0 50px rgba(139, 92, 246, 0.25);
        }

        .gib-doc {
            border-color: rgba(147, 197, 253, 0.55);
            animation-delay: 1.8s;
            box-shadow:
                0 0 0 1px rgba(147, 197, 253, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.18),
                inset 0 -1px 0 rgba(0, 0, 0, 0.3),
                0 8px 32px rgba(0, 0, 0, 0.5),
                0 0 22px rgba(96, 165, 250, 0.5),
                0 0 50px rgba(96, 165, 250, 0.22);
        }

        /* Hover — brighter glow + slight scale */
        .float-icon:hover .glass-icon-box {
            transform: scale(1.1);
        }

        .float-icon:hover .gib-video {
            box-shadow:
                0 0 0 2px rgba(56, 189, 248, 0.5),
                0 12px 40px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(56, 189, 248, 0.7),
                0 0 80px rgba(56, 189, 248, 0.35);
        }

        .float-icon:hover .gib-mic {
            box-shadow:
                0 0 0 2px rgba(167, 139, 250, 0.5),
                0 12px 40px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(139, 92, 246, 0.7),
                0 0 80px rgba(139, 92, 246, 0.35);
        }

        .float-icon:hover .gib-doc {
            box-shadow:
                0 0 0 2px rgba(147, 197, 253, 0.5),
                0 12px 40px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(96, 165, 250, 0.7),
                0 0 80px rgba(96, 165, 250, 0.35);
        }

        /* Glow pulse animation */
        @keyframes glowPulse {

            0%,
            100% {
                filter: brightness(1);
            }

            50% {
                filter: brightness(1.25);
            }
        }

        /* ── Float keyframes — pure Y translation, tilt is on separate inner el ── */
        .float-icon {
            display: inline-block;
        }

        @keyframes floatA {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-11px);
            }
        }

        @keyframes floatB {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes floatC {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-9px);
            }
        }

        /* ── 4 Nebula orbit ring animations ── */
        @keyframes orbitSpin1 {
            from {
                transform: rotateX(68deg) rotateZ(0deg);
            }

            to {
                transform: rotateX(68deg) rotateZ(360deg);
            }
        }

        @keyframes orbitSpin2 {
            from {
                transform: rotateX(68deg) rotateZ(55deg);
            }

            to {
                transform: rotateX(68deg) rotateZ(-305deg);
            }
        }

        @keyframes orbitSpin3 {
            from {
                transform: rotateX(75deg) rotateZ(110deg);
            }

            to {
                transform: rotateX(75deg) rotateZ(470deg);
            }
        }

        @keyframes orbitSpin4 {
            from {
                transform: rotateX(62deg) rotateZ(165deg);
            }

            to {
                transform: rotateX(62deg) rotateZ(-195deg);
            }
        }
    </style>

    <script>
        (function() {
            const hour = new Date().getHours();
            let greeting = 'Selamat Pagi';
            if (hour >= 18) greeting = 'Selamat Malam';
            else if (hour >= 15) greeting = 'Selamat Sore';
            else if (hour >= 11) greeting = 'Selamat Siang';
            const el = document.getElementById('greetingText');
            if (el) el.textContent = greeting;
        })();
    </script>

@endsection
