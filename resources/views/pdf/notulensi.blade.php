<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Notulensi — {{ $meeting->nama_rapat }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #111;
        }

        h1 {
            font-size: 16pt;
            margin-bottom: 0.2em;
        }

        h2 {
            font-size: 12pt;
            margin-top: 1.2em;
        }

        .meta {
            font-size: 9pt;
            color: #444;
            margin-bottom: 1em;
        }

        ul {
            margin: 0.2em 0 0.6em 1.2em;
        }

        .box {
            border: 1px solid #ccc;
            padding: 8px;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <h1>Notulensi Rapat</h1>
    <div class="meta">
        <div><strong>Judul:</strong> {{ $meeting->nama_rapat }}</div>
        <div><strong>Tanggal:</strong> {{ $meeting->tanggal?->format('d/m/Y') }}</div>
        <div><strong>Waktu:</strong> {{ $meeting->waktu }}</div>
        <div><strong>Meeting ID:</strong> {{ $meeting->id }}</div>
    </div>

    <h2>Ringkasan</h2>
    <div class="box">
        {!! nl2br(e($notulensi->ringkasan)) !!}
    </div>

    @php($s = $notulensi->structured_summary ?? [])

    @if (!empty($s['topik_dibahas']) && is_array($s['topik_dibahas']))
        <h2>Topik Dibahas</h2>
        <ul>
            @foreach ($s['topik_dibahas'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif

    @if (!empty($s['keputusan']) && is_array($s['keputusan']))
        <h2>Keputusan</h2>
        <ul>
            @foreach ($s['keputusan'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif

    @if (!empty($s['action_items']) && is_array($s['action_items']))
        <h2>Action Items</h2>
        <table width="100%" cellpadding="4" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th align="left">Tugas</th>
                    <th align="left">PIC</th>
                    <th align="left">Deadline</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($s['action_items'] as $row)
                    @if (is_array($row))
                        <tr>
                            <td>{{ $row['task'] ?? '' }}</td>
                            <td>{{ $row['pic'] ?? '' }}</td>
                            <td>{{ $row['deadline'] ?? '' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

    @if (!empty($s['risiko_catatan']) && is_array($s['risiko_catatan']))
        <h2>Risiko / Catatan</h2>
        <ul>
            @foreach ($s['risiko_catatan'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif

    <p class="meta" style="margin-top: 2em;">
        Dibuat otomatis — prompt {{ $notulensi->prompt_version ?? '-' }} — model {{ $notulensi->openai_model ?? '-' }}
    </p>
</body>

</html>
