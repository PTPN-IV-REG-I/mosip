<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="subtitle">Monitoring & analitik sistem keseluruhan</x-slot>

    <div class="mb-8 grid gap-5 xl:grid-cols-[1.4fr_.9fr]">
        <div class="glass-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-600">System Control</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Admin Dashboard</h2>
                    <p class="mt-2 text-sm text-slate-500">Pantau kesehatan sistem, performa request, distribusi role, dan log operasional dalam satu workspace yang rapi dan responsif.</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-sync-status status="online" label="Sistem Normal" time="Ping 42ms"/>
                    <x-button variant="secondary" id="btn-refresh-admin">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </x-button>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <p class="text-sm font-semibold text-slate-800">Status Node</p>
            <div class="mt-5 space-y-3">
                @foreach([
                    ['API Gateway','Healthy','emerald'],
                    ['Database','Stable','primary'],
                    ['Queue Worker','Warning','amber'],
                ] as [$service,$state,$tone])
                    <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                        <span class="text-sm text-slate-600">{{ $service }}</span>
                        <span class="rounded-full px-3 py-1 text-[11px] font-bold {{ $tone === 'emerald' ? 'bg-emerald-100 text-emerald-700' : ($tone === 'amber' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700') }}">{{ $state }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @foreach([
            ['98.7%','Uptime Sistem','from-emerald-500 to-teal-600','M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['1,248','Total Pengguna','from-primary-500 to-indigo-600','M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['4.2k','Request Hari Ini','from-purple-500 to-violet-600','M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['12','Error Aktif','from-red-500 to-rose-600','M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ] as $k)
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $k[1] }}</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1 leading-none">{{ $k[0] }}</p>
                </div>
                <div class="stat-card-icon bg-gradient-to-br {{ $k[2] }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $k[3] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
        <x-card title="Request per Jam (Hari Ini)">
            <div id="chart-request" style="min-height:220px"></div>
        </x-card>
        <x-card title="Distribusi Role Pengguna">
            <div id="chart-roles" style="min-height:220px"></div>
        </x-card>
    </div>

    {{-- System log table --}}
    <x-card title="Log Sistem Terbaru">
        <x-slot name="actions">
            <x-button variant="secondary" id="btn-log-export">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Log
            </x-button>
        </x-slot>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Level</th>
                        <th>Modul</th>
                        <th>Pesan</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['13:42:01','INFO','Auth','Login berhasil','admin@mosip.go.id'],
                        ['13:38:17','WARNING','Berkas','Ukuran file melebihi batas','budi.r@sipil.go.id'],
                        ['13:31:05','INFO','Sinkronisasi','Data berhasil disinkronkan','Sistem'],
                        ['13:20:44','ERROR','Database','Timeout query laporan','Sistem'],
                        ['13:15:22','INFO','Auth','Token diperbarui','dewi.p@sipil.go.id'],
                        ['13:09:11','INFO','Berkas','Berkas #1248 disetujui','admin@mosip.go.id'],
                    ] as $log)
                    <tr>
                        <td class="text-xs font-mono text-slate-500">{{ $log[0] }}</td>
                        <td>
                            <x-badge value="{{ strtolower($log[1]==='INFO' ? 'info' : ($log[1]==='WARNING' ? 'warning' : 'danger')) }}" />
                        </td>
                        <td class="font-medium text-slate-700">{{ $log[2] }}</td>
                        <td class="text-slate-600">{{ $log[3] }}</td>
                        <td class="text-xs text-slate-500">{{ $log[4] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    new ApexCharts(document.getElementById('chart-request'), {
        chart: { type:'line', height:220, toolbar:{ show:false }, animations:{ enabled:true, easing:'easeinout', speed:700 } },
        series: [{ name:'Request', data:[42,68,91,73,112,98,134,156,143,178,201,185,162,190,214,198,172,144] }],
        xaxis: { categories:['06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'], labels:{ rotate:-45, style:{ fontSize:'10px', colors:'#94a3b8' } } },
        yaxis: { labels:{ style:{ fontSize:'11px', colors:'#94a3b8' } } },
        colors: ['#6366f1'],
        stroke: { curve:'smooth', width:2.5 },
        fill: { type:'gradient', gradient:{ opacityFrom:0.3, opacityTo:0.02, stops:[0,100] } },
        dataLabels: { enabled:false },
        grid: { borderColor:'#f1f5f9', strokeDashArray:4 },
        tooltip: { theme:'light' },
    }).render();

    new ApexCharts(document.getElementById('chart-roles'), {
        chart: { type:'pie', height:220, toolbar:{ show:false }, animations:{ enabled:true, easing:'easeinout', speed:700 } },
        series: [48,312,124,764],
        labels: ['Super Admin','Admin','Verifikator','Pemohon'],
        colors: ['#6366f1','#10b981','#f59e0b','#3b82f6'],
        legend: { position:'bottom', labels:{ colors:'#64748b' }, fontSize:'12px' },
        dataLabels: { style:{ fontSize:'12px' } },
        tooltip: { theme:'light' },
    }).render();
});
</script>
@endpush
