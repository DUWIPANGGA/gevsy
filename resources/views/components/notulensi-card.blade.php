@props([
    'notulensi' => null,
    'ringkasan' => null,
    'topikDibahas' => null,
    'keputusan' => null,
    'actionItems' => null,
    'risikoCatatan' => null,
])

@php
    $ringkasan = $ringkasan ?? ($notulensi['ringkasan'] ?? ($notulensi->ringkasan ?? null));
    $topikDibahas = $topikDibahas ?? ($notulensi['topik_dibahas'] ?? ($notulensi->structured_summary['topik_dibahas'] ?? ($notulensi->topik_dibahas ?? [])));
    $keputusan = $keputusan ?? ($notulensi['keputusan'] ?? ($notulensi->structured_summary['keputusan'] ?? ($notulensi->keputusan ?? [])));
    $actionItems = $actionItems ?? ($notulensi['action_items'] ?? ($notulensi->structured_summary['action_items'] ?? ($notulensi->action_items ?? [])));
    $risikoCatatan = $risikoCatatan ?? ($notulensi['risiko_catatan'] ?? ($notulensi->structured_summary['risiko_catatan'] ?? ($notulensi->risiko_catatan ?? [])));
@endphp

<div class="notulensi-card space-y-5">
    @if($ringkasan)
    <div class="rounded-xl border p-5" style="background:rgba(139,92,246,0.04);border-color:rgba(139,92,246,0.18)">
        <div class="flex items-center gap-2.5 mb-3">
            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <h3 class="text-sm font-bold" style="color:#a78bfa">Ringkasan Eksekutif</h3>
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(139,92,246,0.12);color:#a78bfa">Gemini AI</span>
        </div>
        <p class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--text-secondary, #d1d5db)">{{ $ringkasan }}</p>
    </div>
    @endif

    @if(count($topikDibahas) > 0 || count($keputusan) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @if(count($topikDibahas) > 0)
        <div class="rounded-xl p-5" style="background:rgba(16,185,129,0.03);border:1px solid rgba(16,185,129,0.15)">
            <div class="flex items-center gap-2.5 mb-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h3 class="text-sm font-bold" style="color:#34d399">Topik Dibahas</h3>
            </div>
            <ul class="space-y-2">
                @foreach($topikDibahas as $index => $item)
                <li class="flex items-start gap-2.5">
                    <span class="flex-shrink-0 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center mt-0.5" style="background:rgba(16,185,129,0.12);color:#34d399">{{ $index + 1 }}</span>
                    <span class="text-sm leading-relaxed" style="color:var(--text-secondary, #d1d5db)">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(count($keputusan) > 0)
        <div class="rounded-xl p-5" style="background:rgba(251,191,36,0.03);border:1px solid rgba(251,191,36,0.15)">
            <div class="flex items-center gap-2.5 mb-3">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-bold" style="color:#fbbf24">Keputusan Penting</h3>
            </div>
            <ul class="space-y-2">
                @foreach($keputusan as $item)
                <li class="flex items-start gap-2.5">
                    <svg class="flex-shrink-0 w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:#fbbf24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm leading-relaxed" style="color:var(--text-secondary, #d1d5db)">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    @if(count($actionItems) > 0)
    <div class="rounded-xl p-5" style="background:rgba(56,189,248,0.03);border:1px solid rgba(56,189,248,0.15)">
        <div class="flex items-center gap-2.5 mb-3">
            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <h3 class="text-sm font-bold" style="color:#38bdf8">Action Items</h3>
        </div>
        <div class="overflow-x-auto rounded-lg border" style="border-color:rgba(56,189,248,0.12)">
            <table class="w-full text-left text-sm">
                <thead style="background:rgba(56,189,248,0.05)">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:#38bdf8">Tugas</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:#38bdf8">PIC</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:#38bdf8">Deadline</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:rgba(56,189,248,0.06)">
                    @foreach($actionItems as $item)
                    @php
                        $task = is_array($item) ? ($item['task'] ?? '-') : $item;
                        $pic = is_array($item) ? ($item['pic'] ?? '-') : '-';
                        $deadline = is_array($item) ? ($item['deadline'] ?? '-') : '-';
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm" style="color:var(--text-secondary, #d1d5db)">{{ $task }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full" style="background:rgba(139,92,246,0.1);color:#a78bfa">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $pic }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 text-xs" style="color:var(--text-muted, #9ca3af)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $deadline }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(count($risikoCatatan) > 0)
    <div class="rounded-xl p-5" style="background:rgba(244,63,94,0.03);border:1px solid rgba(244,63,94,0.15)">
        <div class="flex items-center gap-2.5 mb-3">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <h3 class="text-sm font-bold" style="color:#fb7185">Risiko / Catatan</h3>
        </div>
        <ul class="space-y-2">
            @foreach($risikoCatatan as $item)
            <li class="flex items-start gap-2.5">
                <svg class="flex-shrink-0 w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted, #9ca3af)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm leading-relaxed" style="color:var(--text-secondary, #d1d5db)">{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
