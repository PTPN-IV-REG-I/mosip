<x-app-layout>
    <x-slot name="title">Dashboard Utama</x-slot>
    <x-slot name="subtitle">Ringkasan data operasional & perumahan</x-slot>

    {{-- Header --}}
    <div class="mb-8 grid gap-5 xl:grid-cols-[1.4fr_.9fr]">
        <div class="glass-card overflow-hidden">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-600">Overview</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Dashboard MOSIP</h2>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">Pantau operasional sipil dan perumahan dalam satu tampilan modern dengan ringkasan status, tren permohonan, dan aktivitas terbaru.</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <x-sync-status status="online" label="Sinkronisasi Aktif" time="Diperbarui {{ now()->format('H:i') }} WIB"/>
                    <x-button variant="primary" id="btn-export">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </x-button>
                </div>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                @foreach(['Data tersinkron 98.7%','Response API 180ms','31 kecamatan aktif'] as $pill)
                    <span class="rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $pill }}</span>
                @endforeach
            </div>
        </div>

        <div class="glass-card">
            <p class="text-sm font-semibold text-slate-800">Ringkasan Hari Ini</p>
            <div class="mt-5 space-y-4">
                @foreach([
                    ['Berkas baru','86','bg-primary-500'],
                    ['Verifikasi selesai','54','bg-emerald-500'],
                    ['Butuh tindak lanjut','12','bg-amber-500'],
                ] as [$label,$value,$dot])
                    <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>
                            <span class="text-sm text-slate-600">{{ $label }}</span>
                        </div>
                        <span class="text-lg font-bold text-slate-800">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800">Executive Snapshot</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="text-xs text-slate-400">React-like motion, Blade-native rendering</div>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <x-stat-card value="1,248" label="Total Pemohon" change="+12.4%" color="from-primary-500 to-indigo-600">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></x-slot>
        </x-stat-card>
        <x-stat-card value="876" label="Berkas Diproses" change="+8.1%" color="from-emerald-500 to-teal-600">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot>
        </x-stat-card>
        <x-stat-card value="243" label="Menunggu Verifikasi" change="-3.2%" color="from-amber-500 to-orange-500">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot>
        </x-stat-card>
        <x-stat-card value="129" label="Ditolak / Revisi" change="-1.7%" color="from-red-500 to-rose-600">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot>
        </x-stat-card>
    </div>

    {{-- Charts row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        <x-card title="Tren Permohonan Bulanan" class="lg:col-span-2">
            <x-slot name="actions">
                <div class="flex gap-1 rounded-2xl bg-slate-100/80 p-1">
                    @foreach(['6B','3B','1B'] as $i => $opt)
                        <button class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all duration-150 {{ $i===0 ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-500 hover:bg-white/70' }}">{{ $opt }}</button>
                    @endforeach
                </div>
            </x-slot>
            <div id="chart-trend" style="min-height:260px"></div>
        </x-card>
        <x-card title="Status Berkas">
            <div id="chart-status" style="min-height:260px"></div>
        </x-card>
    </div>

    {{-- Bottom row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <x-card title="Aktivitas Terbaru">
            <div class="space-y-1">
                @foreach([
                    ['Berkas #BRK-1248 disetujui','Agus Santoso','5 mnt lalu','success'],
                    ['Permohonan baru #BRK-1247 masuk','Budi Raharjo','22 mnt lalu','info'],
                    ['Berkas #BRK-1241 butuh revisi','Sistem','1 jam lalu','warning'],
                    ['User baru didaftarkan','Admin','2 jam lalu','info'],
                    ['Laporan bulanan diekspor','Sistem','3 jam lalu','success'],
                ] as $act)
                <div class="flex items-start gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center
                        {{ $act[3]==='success' ? 'bg-emerald-100' : ($act[3]==='warning' ? 'bg-amber-100' : 'bg-primary-100') }}">
                        @if($act[3]==='success')
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($act[3]==='warning')
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        @else
                            <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $act[0] }}</p>
                        <p class="text-xs text-slate-400">{{ $act[1] }} · {{ $act[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </x-card>

        <x-card title="Distribusi per Kecamatan">
            <div id="chart-wilayah" style="min-height:280px"></div>
        </x-card>
    </div>

</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const baseOpts = {
        chart: { toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 700 } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { theme: 'light' },
    };

    new ApexCharts(document.getElementById('chart-trend'), {
        ...baseOpts,
        chart: { ...baseOpts.chart, type: 'area', height: 260 },
        series: [
            { name: 'Permohonan', data: [142,198,174,221,189,263,295,318,272,341,388,420] },
            { name: 'Disetujui',  data: [98,145,121,178,143,201,237,264,215,288,311,356] }
        ],
        xaxis: { categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'], labels: { style: { fontSize:'11px', colors:'#94a3b8' } } },
        yaxis: { labels: { style: { fontSize:'11px', colors:'#94a3b8' } } },
        colors: ['#6366f1','#10b981'],
        fill: { type:'gradient', gradient: { opacityFrom:0.35, opacityTo:0.02, stops:[0,100] } },
        stroke: { curve:'smooth', width:2.5 },
        dataLabels: { enabled: false },
        legend: { position:'top', horizontalAlign:'right', labels:{ colors:'#64748b' }, fontSize:'12px' },
    }).render();

    new ApexCharts(document.getElementById('chart-status'), {
        ...baseOpts,
        chart: { ...baseOpts.chart, type:'donut', height:260 },
        series: [876,243,129],
        labels: ['Disetujui','Menunggu','Ditolak'],
        colors: ['#10b981','#f59e0b','#ef4444'],
        plotOptions: { pie: { donut: { size:'70%', labels: { show:true, total: { show:true, label:'Total', fontSize:'13px', color:'#64748b', formatter:()=>'1,248' } } } } },
        legend: { position:'bottom', labels:{ colors:'#64748b' }, fontSize:'12px' },
        dataLabels: { enabled:false },
    }).render();

    new ApexCharts(document.getElementById('chart-wilayah'), {
        ...baseOpts,
        chart: { ...baseOpts.chart, type:'bar', height:280 },
        series: [{ name:'Berkas', data:[312,198,267,143,178,150] }],
        xaxis: { categories:['Kec. Pusat','Kec. Barat','Kec. Timur','Kec. Utara','Kec. Selatan','Kec. Luar'], labels:{ style:{ fontSize:'11px', colors:'#94a3b8' } } },
        yaxis: { labels:{ style:{ fontSize:'11px', colors:'#94a3b8' } } },
        colors: ['#6366f1'],
        plotOptions: { bar: { borderRadius:8, columnWidth:'50%', dataLabels:{ position:'top' } } },
        dataLabels: { enabled:true, style:{ fontSize:'11px', colors:['#6366f1'] }, offsetY:-18 },
    }).render();
});
</script>
@endpush
