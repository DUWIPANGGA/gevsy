<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notulensi Audio — {{ $liveAudio->tanggal_rekam?->format('d F Y') ?? $liveAudio->created_at->format('d F Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5pt;
            color: #1a1a2e;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            background: #0284c7;
            color: white;
            padding: 22px 32px 20px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .header .subtitle {
            font-size: 9pt;
            opacity: 0.85;
        }
        .header-badge {
            background: rgba(255,255,255,0.22);
            border: 1px solid rgba(255,255,255,0.35);
            color: white;
            font-size: 8pt;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* ===== CONTENT ===== */
        .content { padding: 24px 32px 32px; }

        /* ===== META TABLE ===== */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            border: 1px solid #dde3f0;
            border-radius: 6px;
            overflow: hidden;
        }
        .meta-table tr { background: #f8f9ff; }
        .meta-table tr:nth-child(even) { background: #fff; }
        .meta-table td {
            padding: 7px 14px;
            font-size: 9.5pt;
            border-bottom: 1px solid #eef0f8;
        }
        .meta-table td.label {
            color: #6b7280;
            font-weight: bold;
            width: 30%;
        }
        .meta-table tr:last-child td { border-bottom: none; }
        .badge-done {
            background: #d1fae5;
            color: #065f46;
            font-size: 8pt;
            font-weight: bold;
            padding: 2px 10px;
            border-radius: 20px;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-header {
            padding: 10px 16px;
            font-size: 10.5pt;
            font-weight: bold;
            color: #fff;
        }
        .section-body { padding: 12px 16px; }

        /* Header colors per section */
        .header-blue   { background: #0284c7; }
        .header-green  { background: #16a34a; }
        .header-orange { background: #ea580c; }
        .header-red    { background: #dc2626; }

        /* ===== RINGKASAN ===== */
        .ringkasan-box {
            background: #eff6ff;
            border-left: 4px solid #0284c7;
            padding: 12px 14px;
            border-radius: 0 6px 6px 0;
            font-size: 10pt;
            line-height: 1.65;
            color: #1e3a5f;
        }

        /* ===== LIST ===== */
        .item-list { list-style: none; }
        .item-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 10pt;
            line-height: 1.5;
            color: #374151;
        }
        .item-list li:last-child { border-bottom: none; }
        .bullet {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }
        .bullet-blue   { background: #0284c7; }
        .bullet-green  { background: #16a34a; }
        .bullet-red    { background: #dc2626; }
        .num-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            line-height: 20px;
            flex-shrink: 0;
        }

        /* ===== ACTION ITEMS TABLE ===== */
        .action-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        .action-table th {
            background: #fff7ed;
            color: #c2410c;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 2px solid #fed7aa;
        }
        .action-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: top;
        }
        .action-table tr:last-child td { border-bottom: none; }
        .action-table tr:nth-child(even) td { background: #fffbf7; }
        .pic-pill {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 20px;
        }
        .deadline-text { color: #6b7280; font-size: 9pt; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 28px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 8pt;
            color: #9ca3af;
            text-align: center;
        }
        .footer strong { color: #6b7280; }
    </style>
</head>
<body>

    {{-- ===== HEADER ===== --}}
    <div class="header">
        <div class="header-top">
            <div>
                <h1>Notulensi Audio Rapat</h1>
                <p class="subtitle">
                    Direkam pada {{ $liveAudio->tanggal_rekam?->format('d F Y, H:i') ?? $liveAudio->created_at->format('d F Y, H:i') }} WIB
                    &nbsp;&bull;&nbsp; Ukuran: {{ number_format($liveAudio->file_size_bytes / 1024 / 1024, 2) }} MB
                </p>
            </div>
            <span class="header-badge">Gemini AI &bull; v1</span>
        </div>
    </div>

    <div class="content">

        {{-- ===== META INFO ===== --}}
        <table class="meta-table">
            <tr>
                <td class="label">Tanggal Rekam</td>
                <td>{{ $liveAudio->tanggal_rekam?->format('d F Y') ?? '-' }}</td>
                <td class="label">Waktu</td>
                <td>{{ $liveAudio->tanggal_rekam?->format('H:i') ?? '-' }} WIB</td>
            </tr>
            <tr>
                <td class="label">Dibuat Oleh</td>
                <td>{{ $liveAudio->user->name ?? 'Pengguna' }}</td>
                <td class="label">Status</td>
                <td>
                    @if($notulensi && !isset($notulensi['error']))
                        <span class="badge-done">✓ Gemini Selesai</span>
                    @else
                        <span style="color:#dc2626;font-weight:bold;">✗ Gagal</span>
                    @endif
                </td>
            </tr>
        </table>

        @if($notulensi && isset($notulensi['error']))
            <p style="color:#dc2626;padding:10px;background:#fef2f2;border-radius:6px;">
                <strong>Error AI:</strong> {{ $notulensi['error'] }}
            </p>
        @elseif($notulensi)

            {{-- ===== RINGKASAN ===== --}}
            @if(!empty($notulensi['ringkasan']))
            <div class="section">
                <div class="section-header header-blue">📋 Ringkasan Eksekutif</div>
                <div class="section-body">
                    <div class="ringkasan-box">{{ $notulensi['ringkasan'] }}</div>
                </div>
            </div>
            @endif

            {{-- ===== TOPIK DIBAHAS ===== --}}
            @if(!empty($notulensi['topik_dibahas']) && is_array($notulensi['topik_dibahas']))
            <div class="section">
                <div class="section-header header-blue">📌 Topik Dibahas ({{ count($notulensi['topik_dibahas']) }} topik)</div>
                <div class="section-body">
                    <ul class="item-list">
                        @foreach($notulensi['topik_dibahas'] as $i => $item)
                        <li>
                            <span class="num-badge">{{ $i + 1 }}</span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- ===== KEPUTUSAN ===== --}}
            @if(!empty($notulensi['keputusan']) && is_array($notulensi['keputusan']))
            <div class="section">
                <div class="section-header header-green">✅ Keputusan Rapat ({{ count($notulensi['keputusan']) }} keputusan)</div>
                <div class="section-body">
                    <ul class="item-list">
                        @foreach($notulensi['keputusan'] as $item)
                        <li>
                            <span class="bullet bullet-green"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- ===== ACTION ITEMS ===== --}}
            {{-- Gemini format: [{"task":"...", "pic":"...", "deadline":"..."}] --}}
            @if(!empty($notulensi['action_items']) && is_array($notulensi['action_items']))
            <div class="section">
                <div class="section-header header-orange">⚡ Action Items ({{ count($notulensi['action_items']) }} item)</div>
                <div class="section-body" style="padding:0;">
                    <table class="action-table">
                        <thead>
                            <tr>
                                <th style="width:50%">Tugas</th>
                                <th style="width:25%">PIC</th>
                                <th style="width:25%">Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notulensi['action_items'] as $item)
                            <tr>
                                <td>
                                    @if(is_array($item))
                                        {{ $item['task'] ?? '-' }}
                                    @else
                                        {{ $item }}
                                    @endif
                                </td>
                                <td>
                                    @if(is_array($item) && !empty($item['pic']) && $item['pic'] !== '-')
                                        <span class="pic-pill">{{ $item['pic'] }}</span>
                                    @else
                                        <span class="deadline-text">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="deadline-text">
                                        @if(is_array($item))
                                            {{ $item['deadline'] ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- ===== RISIKO & CATATAN ===== --}}
            {{-- Gemini key: "risiko_catatan" --}}
            @if(!empty($notulensi['risiko_catatan']) && is_array($notulensi['risiko_catatan']))
            <div class="section">
                <div class="section-header header-red">⚠️ Risiko & Catatan ({{ count($notulensi['risiko_catatan']) }} catatan)</div>
                <div class="section-body">
                    <ul class="item-list">
                        @foreach($notulensi['risiko_catatan'] as $item)
                        <li>
                            <span class="bullet bullet-red"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

        @else
            <p style="color:#6b7280;font-style:italic;text-align:center;padding:20px;">
                Notulensi belum tersedia — Gemini AI masih memproses rekaman.
            </p>
        @endif

        {{-- ===== FOOTER ===== --}}
        <div class="footer">
            Dokumen ini dibuat otomatis oleh <strong>Gemini AI Notulensi</strong>
            &bull; Endpoint: <strong>api.siputzx.my.id/api/ai/gemini</strong>
            &bull; Dicetak: {{ now()->format('d F Y, H:i') }} WIB
        </div>

    </div>
</body>
</html>
