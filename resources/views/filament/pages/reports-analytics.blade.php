<x-filament-panels::page>
    @php $d = $this->getData(); @endphp

    <style>
        .ra { --ra-green:#316d31; --ra-green-soft:#eef6ee; --ra-navy:#022251; --ra-border:#e5e7eb; --ra-muted:#64748b; --ra-track:#f1f5f9;
              display:flex; flex-direction:column; gap:20px; }
        .dark .ra { --ra-border:rgba(255,255,255,.1); --ra-muted:#94a3b8; --ra-track:rgba(255,255,255,.08); }
        .ra-card { background:#fff; border:1px solid var(--ra-border); border-radius:16px; }
        .dark .ra-card { background:rgb(24 24 27); }
        .ra-head { padding:16px 20px; border-bottom:1px solid var(--ra-border); font-size:14px; font-weight:700; }
        .ra-body { padding:16px 20px; }
        .ra-totals { display:grid; gap:14px; grid-template-columns:repeat(4, minmax(0,1fr)); }
        @media (max-width:900px) { .ra-totals { grid-template-columns:repeat(2, minmax(0,1fr)); } }
        .ra-total { padding:18px; }
        .ra-total b { display:block; font-size:28px; font-weight:800; line-height:1; font-variant-numeric:tabular-nums; }
        .ra-total span { display:block; margin-top:6px; font-size:12.5px; font-weight:600; color:var(--ra-muted); }
        .ra-grid { display:grid; gap:20px; grid-template-columns:repeat(2, minmax(0,1fr)); }
        .ra-grid-3 { display:grid; gap:20px; grid-template-columns:repeat(3, minmax(0,1fr)); }
        @media (max-width:1024px) { .ra-grid, .ra-grid-3 { grid-template-columns:minmax(0,1fr); } }
        .ra-row { display:grid; grid-template-columns:52px 1fr 70px; gap:12px; align-items:center; padding:7px 0; font-size:13px; }
        .ra-code { font-size:10.5px; font-weight:800; letter-spacing:.06em; padding:2px 6px; border-radius:6px; background:var(--ra-navy); color:#fff; text-align:center; }
        .ra-stack { display:flex; height:10px; border-radius:99px; overflow:hidden; background:var(--ra-track); }
        .ra-stack i { display:block; height:100%; }
        .ra-num { text-align:right; font-variant-numeric:tabular-nums; color:var(--ra-muted); font-size:12.5px; }
        .ra-legend { display:flex; gap:16px; font-size:12px; color:var(--ra-muted); margin-bottom:10px; }
        .ra-legend i { display:inline-block; width:10px; height:10px; border-radius:3px; margin-right:6px; vertical-align:-1px; }
        .ra-kv { display:flex; justify-content:space-between; padding:8px 0; border-top:1px solid var(--ra-border); font-size:13px; }
        .ra-kv:first-child { border-top:0; }
        .ra-kv b { font-variant-numeric:tabular-nums; }
        .ra-months { display:grid; grid-template-columns:repeat(12, 1fr); gap:8px; align-items:end; height:180px; }
        .ra-month { display:flex; flex-direction:column; align-items:center; gap:6px; height:100%; justify-content:flex-end; }
        .ra-month-bars { display:flex; gap:3px; align-items:flex-end; height:100%; }
        .ra-month-bars i { display:block; width:10px; border-radius:4px 4px 0 0; min-height:2px; }
        .ra-month span { font-size:11px; color:var(--ra-muted); }
    </style>

    <div class="ra">
        <div class="ra-totals">
            @foreach ($d['totals'] as [$label, $value])
                <div class="ra-card ra-total"><b>{{ number_format($value) }}</b><span>{{ $label }}</span></div>
            @endforeach
        </div>

        {{-- Activity by department --}}
        <section class="ra-card">
            <div class="ra-head">Activity by department</div>
            <div class="ra-body">
                <div class="ra-legend">
                    <span><i style="background:var(--ra-green)"></i>Projects</span>
                    <span><i style="background:#8fbf8b"></i>Ideas submitted</span>
                </div>
                @foreach ($d['byDepartment'] as $row)
                    <div class="ra-row" title="{{ $row['name'] }}">
                        <span class="ra-code">{{ $row['code'] }}</span>
                        <span class="ra-stack">
                            <i style="width: {{ $row['projects'] / $d['departmentMax'] * 100 }}%; background:var(--ra-green)"></i>
                            <i style="width: {{ $row['ideas'] / $d['departmentMax'] * 100 }}%; background:#8fbf8b"></i>
                        </span>
                        <span class="ra-num">{{ $row['projects'] }} · {{ $row['ideas'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Incoming, month by month --}}
        <section class="ra-card">
            <div class="ra-head">Incoming, last 12 months</div>
            <div class="ra-body">
                <div class="ra-legend">
                    <span><i style="background:var(--ra-green)"></i>Ideas</span>
                    <span><i style="background:var(--ra-navy)"></i>Consultancy requests</span>
                </div>
                <div class="ra-months">
                    @foreach ($d['months'] as $m)
                        <div class="ra-month" title="{{ $m['label'] }}: {{ $m['ideas'] }} ideas, {{ $m['requests'] }} requests">
                            <div class="ra-month-bars">
                                <i style="height: {{ $m['ideas'] / $d['monthMax'] * 100 }}%; background:var(--ra-green)"></i>
                                <i style="height: {{ $m['requests'] / $d['monthMax'] * 100 }}%; background:var(--ra-navy)"></i>
                            </div>
                            <span>{{ $m['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="ra-grid-3">
            @foreach ([['Projects by type', $d['byType']], ['Projects by status', $d['byStatus']], ['Ideas by who submitted', $d['ideasByRole']]] as [$title, $rows])
                <section class="ra-card">
                    <div class="ra-head">{{ $title }}</div>
                    <div class="ra-body">
                        @foreach ($rows as [$label, $count])
                            <div class="ra-kv"><span>{{ $label }}</span><b>{{ $count }}</b></div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        <section class="ra-card">
            <div class="ra-head">Consultancy requests by status</div>
            <div class="ra-body">
                @forelse ($d['requestsByStatus'] as $status => $count)
                    <div class="ra-kv"><span>{{ str($status)->replace('_', ' ')->title() }}</span><b>{{ $count }}</b></div>
                @empty
                    <p style="font-size:13px; color:var(--ra-muted)">No requests yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-filament-panels::page>
