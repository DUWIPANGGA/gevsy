<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notulensi — {{ $meeting->nama_rapat }}</title>
    
    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (for modern structures) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4 {
            font-family: 'Outfit', sans-serif;
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen pb-12 selection:bg-violet-500/30 selection:text-violet-200">

    <!-- Gradient Accents -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-emerald-600/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        
        <!-- Navigation Breadcrumbs / Header Buttons -->
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('meeting.room', $meeting->id) }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition group">
                <span class="transform group-hover:-translate-x-1 transition-transform duration-200">←</span> Kembali ke Meeting Room
            </a>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('meeting.notulensi.pdf', $meeting->id) }}" target="_blank" rel="noopener" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-emerald-950/20 transition flex items-center gap-2">
                    📄 Cetak / Unduh PDF
                </a>
            </div>
        </div>

        <!-- Main Content Glassmorphic Wrapper -->
        <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8 relative overflow-hidden">
            
            <!-- Glow effect border decoration -->
            <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-violet-500 via-indigo-500 to-emerald-500"></div>

            <!-- Title & Meta Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="px-3 py-1 rounded-full bg-violet-500/10 border border-violet-500/25 text-[11px] font-bold tracking-wider uppercase text-violet-400">
                        AI Generated Minutes
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $meeting->nama_rapat }}
                </h1>
                
                <!-- Meta information grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-gray-800 text-gray-400 text-xs sm:text-sm">
                    <div>
                        <span class="block text-gray-500 text-[10px] sm:text-xs uppercase tracking-wider">Tanggal</span>
                        <strong class="text-gray-200 mt-1 block">{{ $meeting->tanggal?->format('d F Y') ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-[10px] sm:text-xs uppercase tracking-wider">Waktu Rapat</span>
                        <strong class="text-gray-200 mt-1 block">{{ $meeting->waktu ?? '-' }}</strong>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-[10px] sm:text-xs uppercase tracking-wider">Meeting ID</span>
                        <strong class="text-gray-200 mt-1 block font-mono">#{{ $meeting->id }}</strong>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-[10px] sm:text-xs uppercase tracking-wider">Dibuat Oleh</span>
                        <strong class="text-gray-200 mt-1 block">{{ $meeting->creator?->name ?? 'Moderator' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Executive Summary -->
            <div class="bg-gray-900/60 border border-gray-800/80 p-6 rounded-2xl space-y-3">
                <h3 class="text-xs sm:text-sm font-bold text-violet-400 uppercase tracking-widest flex items-center gap-2.5">
                    <span class="flex h-2 w-2 rounded-full bg-violet-400"></span> 📝 Ringkasan Eksekutif
                </h3>
                <p class="text-sm sm:text-base leading-relaxed text-gray-200 whitespace-pre-line">
                    {{ $notulensi->ringkasan }}
                </p>
            </div>

            @php($s = $notulensi->structured_summary ?? [])

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Topics Discussed -->
                @if(!empty($s['topik_dibahas']) && is_array($s['topik_dibahas']))
                <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl space-y-4">
                    <h3 class="text-xs sm:text-sm font-bold text-emerald-400 uppercase tracking-widest flex items-center gap-2.5">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-400"></span> 📌 Topik Dibahas
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($s['topik_dibahas'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-300">
                            <span class="text-emerald-400 mt-0.5">•</span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Decisions -->
                @if(!empty($s['keputusan']) && is_array($s['keputusan']))
                <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl space-y-4">
                    <h3 class="text-xs sm:text-sm font-bold text-amber-400 uppercase tracking-widest flex items-center gap-2.5">
                        <span class="flex h-2 w-2 rounded-full bg-amber-400"></span> 💡 Keputusan Penting
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($s['keputusan'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-gray-300">
                            <span class="text-amber-400 mt-0.5">•</span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <!-- Action Items -->
            @if(!empty($s['action_items']) && is_array($s['action_items']))
            <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl space-y-4">
                <h3 class="text-xs sm:text-sm font-bold text-sky-400 uppercase tracking-widest flex items-center gap-2.5">
                    <span class="flex h-2 w-2 rounded-full bg-sky-400"></span> 🏃 Action Items (Rencana Tindakan)
                </h3>
                <div class="overflow-x-auto rounded-xl border border-gray-850 bg-gray-950/40">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-gray-900/80 text-xs uppercase text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-5 py-3.5">Tugas</th>
                                <th class="px-5 py-3.5">Penanggung Jawab (PIC)</th>
                                <th class="px-5 py-3.5">Batas Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-850">
                            @foreach ($s['action_items'] as $row)
                            @if (is_array($row))
                            <tr class="hover:bg-gray-900/40 transition-colors">
                                <td class="px-5 py-4 font-medium text-gray-200 leading-normal">{{ $row['task'] ?? '' }}</td>
                                <td class="px-5 py-4 text-gray-300">{{ $row['pic'] ?? '' }}</td>
                                <td class="px-5 py-4 text-gray-400 font-mono text-xs">{{ $row['deadline'] ?? '' }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Risks & Notes -->
            @if(!empty($s['risiko_catatan']) && is_array($s['risiko_catatan']))
            <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl space-y-4">
                <h3 class="text-xs sm:text-sm font-bold text-rose-400 uppercase tracking-widest flex items-center gap-2.5">
                    <span class="flex h-2 w-2 rounded-full bg-rose-400"></span> ⚠️ Risiko / Catatan Penting
                </h3>
                <ul class="space-y-3">
                    @foreach ($s['risiko_catatan'] as $item)
                    <li class="flex items-start gap-2.5 text-sm text-gray-300">
                        <span class="text-rose-400 mt-0.5">•</span>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Footer Details -->
            <div class="pt-6 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500 gap-2">
                <span>Model AI: <strong class="text-gray-400">{{ $notulensi->openai_model ?? 'Gemini' }}</strong></span>
                <span>ID Notulensi: <span class="font-mono">#NT-{{ $notulensi->id }}</span></span>
                <span>Dibuat otomatis pada {{ $notulensi->created_at?->format('d/m/Y H:i') }}</span>
            </div>

        </div>
        
    </div>

</body>

</html>
