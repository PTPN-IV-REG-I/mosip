<x-modern-layout title="Dashboard MOSIP" subtitle="Semua Informasi dalam Satu Tampilan">
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const baseOpts = {
            chart: { toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 700 } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
            tooltip: { theme: 'light' },
        };

        new ApexCharts(document.getElementById('chart-trend'), {
            ...baseOpts,
            chart: { ...baseOpts.chart, type: 'area', height: 280 },
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
            chart: { ...baseOpts.chart, type:'donut', height: 280 },
            series: [876,243,129],
            labels: ['Disetujui','Menunggu','Ditolak'],
            colors: ['#10b981','#f59e0b','#ef4444'],
            plotOptions: { pie: { donut: { size:'70%', labels: { show:true, total: { show:true, label:'Total', fontSize:'13px', color:'#64748b', formatter:()=>'1,248' } } } } },
            legend: { position:'bottom', labels:{ colors:'#64748b' }, fontSize:'12px' },
            dataLabels: { enabled:false },
        }).render();

        new ApexCharts(document.getElementById('chart-wilayah'), {
            ...baseOpts,
            chart: { ...baseOpts.chart, type:'bar', height: 280 },
            series: [{ name:'Berkas', data:[312,198,267,143,178,150] }],
            xaxis: { categories:['Kec. Pusat','Kec. Barat','Kec. Timur','Kec. Utara','Kec. Selatan','Kec. Luar'], labels:{ style:{ fontSize:'11px', colors:'#94a3b8' } } },
            yaxis: { labels: { style: { fontSize:'11px', colors:'#94a3b8' } } },
            colors: ['#6366f1'],
            plotOptions: { bar: { borderRadius:8, columnWidth:'50%', dataLabels:{ position:'top' } } },
            dataLabels: { enabled:true, style:{ fontSize:'11px', colors:['#6366f1'] }, offsetY:-18 },
        }).render();

        new ApexCharts(document.getElementById('chart-rumah'), {
            ...baseOpts,
            chart: { ...baseOpts.chart, type:'bar', height: 260, stacked: true },
            series: [
                { name: 'Baik', data: [44, 55, 41, 67, 22] },
                { name: 'Rusak Ringan', data: [13, 23, 20, 8, 13] },
                { name: 'Rusak Berat', data: [11, 17, 15, 15, 21] }
            ],
            xaxis: { categories: ['Distrik A','Distrik B','Distrik C','Distrik D','Distrik E'], labels:{ style:{ fontSize:'11px', colors:'#94a3b8' } } },
            colors: ['#10b981','#f59e0b','#ef4444'],
            plotOptions: { bar: { borderRadius: 6, horizontal: false } },
            dataLabels: { enabled: false },
            legend: { position:'top', horizontalAlign:'right', labels:{ colors:'#64748b' }, fontSize:'11px' },
        }).render();
    });
    </script>
    @endpush

    <div x-data="dashboardData()">
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-indigo-600 mb-2">Dashboard Utama</p>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-slate-800 via-indigo-700 to-purple-700 bg-clip-text text-transparent">Selamat Datang di MOSIP</h1>
                    <p class="mt-2 text-slate-500">Ringkasan lengkap operasional sipil dan perumahan dalam satu tampilan modern.</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-2xl bg-emerald-50 border border-emerald-200">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-semibold text-emerald-700">Sistem Aktif</span>
                    </div>
                    <button @click="openFullDetail = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-sm shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Lihat Detail Lengkap
                    </button>
                </div>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <span class="px-3 py-1.5 rounded-full bg-white/80 border border-indigo-100 text-xs font-semibold text-indigo-700">Data tersinkron 98.7%</span>
                <span class="px-3 py-1.5 rounded-full bg-white/80 border border-purple-100 text-xs font-semibold text-purple-700">Update: {{ now()->format('H:i') }} WIB</span>
                <span class="px-3 py-1.5 rounded-full bg-white/80 border border-slate-200 text-xs font-semibold text-slate-600">31 kecamatan aktif</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-white via-white to-indigo-50 border border-indigo-100/60 p-6 shadow-xl shadow-indigo-500/10 hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-indigo-400/20 to-purple-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-xl">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            +12.4%
                        </span>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 mb-1">Total Pemohon</p>
                    <p class="text-3xl font-extrabold text-slate-800">1,248</p>
                    <p class="text-xs text-slate-500 mt-1">dari bulan lalu</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-white via-white to-emerald-50 border border-emerald-100/60 p-6 shadow-xl shadow-emerald-500/10 hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-xl">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            +8.1%
                        </span>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 mb-1">Berkas Diproses</p>
                    <p class="text-3xl font-extrabold text-slate-800">876</p>
                    <p class="text-xs text-slate-500 mt-1">dari bulan lalu</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-white via-white to-amber-50 border border-amber-100/60 p-6 shadow-xl shadow-amber-500/10 hover:shadow-2xl hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-amber-400/20 to-orange-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-xl">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            -3.2%
                        </span>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 mb-1">Menunggu Verifikasi</p>
                    <p class="text-3xl font-extrabold text-slate-800">243</p>
                    <p class="text-xs text-slate-500 mt-1">dari bulan lalu</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-white via-white to-red-50 border border-red-100/60 p-6 shadow-xl shadow-red-500/10 hover:shadow-2xl hover:shadow-red-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-red-400/20 to-rose-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-xl">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            -1.7%
                        </span>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 mb-1">Ditolak / Revisi</p>
                    <p class="text-3xl font-extrabold text-slate-800">129</p>
                    <p class="text-xs text-slate-500 mt-1">dari bulan lalu</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
            <div class="lg:col-span-2 rounded-3xl bg-white/80 backdrop-blur-xl border border-white/70 shadow-xl shadow-slate-500/5 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tren Permohonan Bulanan</h3>
                        <p class="text-xs text-slate-500">Perkembangan permohonan 12 bulan terakhir</p>
                    </div>
                    <div class="flex gap-1 rounded-2xl bg-slate-100 p-1">
                        <button class="px-3 py-1.5 rounded-xl text-xs font-medium bg-white text-indigo-700 shadow-sm">6B</button>
                        <button class="px-3 py-1.5 rounded-xl text-xs font-medium text-slate-500 hover:bg-white/70">3B</button>
                        <button class="px-3 py-1.5 rounded-xl text-xs font-medium text-slate-500 hover:bg-white/70">1B</button>
                    </div>
                </div>
                <div id="chart-trend"></div>
            </div>

            <div class="rounded-3xl bg-white/80 backdrop-blur-xl border border-white/70 shadow-xl shadow-slate-500/5 p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Status Berkas</h3>
                    <p class="text-xs text-slate-500">Distribusi status saat ini</p>
                </div>
                <div id="chart-status"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
            <div class="rounded-3xl bg-white/80 backdrop-blur-xl border border-white/70 shadow-xl shadow-slate-500/5 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Kondisi Rumah per Distrik</h3>
                        <p class="text-xs text-slate-500">Ringkasan kondisi rumah permanen & semi permanen</p>
                    </div>
                    <a href="{{ route('tekpol.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div id="chart-rumah"></div>
            </div>

            <div class="rounded-3xl bg-white/80 backdrop-blur-xl border border-white/70 shadow-xl shadow-slate-500/5 p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Distribusi per Kecamatan</h3>
                    <p class="text-xs text-slate-500">Jumlah berkas per wilayah</p>
                </div>
                <div id="chart-wilayah"></div>
            </div>
        </div>

        <div class="rounded-3xl bg-white/80 backdrop-blur-xl border border-white/70 shadow-xl shadow-slate-500/5 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                    <p class="text-xs text-slate-500">5 aktivitas terakhir dalam sistem</p>
                </div>
                <span class="text-xs text-slate-400">Auto-refresh setiap 30 detik</span>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach([
                    ['Berkas #BRK-1248 disetujui','Agus Santoso','5 mnt lalu','success'],
                    ['Permohonan baru #BRK-1247 masuk','Budi Raharjo','22 mnt lalu','info'],
                    ['Berkas #BRK-1241 butuh revisi','Sistem','1 jam lalu','warning'],
                    ['User baru didaftarkan','Admin','2 jam lalu','info'],
                    ['Data rumah diperbarui','Sistem','3 jam lalu','success'],
                ] as $act)
                <div class="px-6 py-4 hover:bg-slate-50/50 transition-colors flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0
                        {{ $act[3]==='success' ? 'bg-emerald-100' : ($act[3]==='warning' ? 'bg-amber-100' : 'bg-indigo-100') }}">
                        @if($act[3]==='success')
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($act[3]==='warning')
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700">{{ $act[0] }}</p>
                        <p class="text-xs text-slate-400">{{ $act[1] }} · {{ $act[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div x-show="openFullDetail" x-cloak class="fixed inset-0 z-[100]">
            <div x-show="openFullDetail" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="openFullDetail = false"
                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            <div x-show="openFullDetail" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="absolute inset-4 sm:inset-8 lg:inset-12 rounded-3xl bg-white shadow-2xl overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Detail Lengkap Data</h2>
                        <p class="text-xs text-slate-500">Semua informasi dalam satu tampilan penuh</p>
                    </div>
                    <button @click="openFullDetail = false" class="p-2 rounded-xl hover:bg-white/80 transition-colors">
                        <svg class="w-6 h-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="rounded-2xl bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 p-5">
                            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                Statistik Permohonan
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-slate-600">Total hari ini</span><span class="font-semibold text-slate-800">86</span></div>
                                <div class="flex justify-between"><span class="text-slate-600">Rata-rata/hari</span><span class="font-semibold text-slate-800">72</span></div>
                                <div class="flex justify-between"><span class="text-slate-600">Target bulan ini</span><span class="font-semibold text-slate-800">1,500</span></div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 p-5">
                            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </span>
                                Kondisi Rumah
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-slate-600">Total Pintu</span><span class="font-semibold text-slate-800">17,623</span></div>
                                <div class="flex justify-between"><span class="text-slate-600">Kondisi Baik</span><span class="font-semibold text-emerald-600">12,890</span></div>
                                <div class="flex justify-between"><span class="text-slate-600">Perlu Perhatian</span><span class="font-semibold text-amber-600">4,733</span></div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-gradient-to-br from-purple-50 to-white border border-purple-100 p-5">
                            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                Wilayah
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-slate-600">Total Distrik</span><span class="font-semibold text-slate-800">5</span></div>
                                <div class="flex justify-between"><span class="text-slate-600">Total Kecamatan</span><span class="font-semibold text-slate-800">31</span></div>
                                <div class="flex justify-between"><span class="text-slate-600">Total Unit</span><span class="font-semibold text-slate-800">44</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                            <h4 class="font-semibold text-slate-800">Ringkasan Data Lengkap</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left font-semibold text-slate-700">Distrik</th>
                                        <th class="px-5 py-3 text-center font-semibold text-slate-700">Total Pintu</th>
                                        <th class="px-5 py-3 text-center font-semibold text-slate-700">Baik</th>
                                        <th class="px-5 py-3 text-center font-semibold text-slate-700">Rusak Ringan</th>
                                        <th class="px-5 py-3 text-center font-semibold text-slate-700">Rusak Berat</th>
                                        <th class="px-5 py-3 text-center font-semibold text-slate-700">Persentase Baik</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 font-medium text-slate-800">Distrik Labuhan Batu</td>
                                        <td class="px-5 py-3 text-center text-slate-600">4,230</td>
                                        <td class="px-5 py-3 text-center text-emerald-600 font-medium">3,120</td>
                                        <td class="px-5 py-3 text-center text-amber-600">890</td>
                                        <td class="px-5 py-3 text-center text-red-600">220</td>
                                        <td class="px-5 py-3 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500" style="width: 74%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-700">74%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 font-medium text-slate-800">Distrik Asahan</td>
                                        <td class="px-5 py-3 text-center text-slate-600">3,890</td>
                                        <td class="px-5 py-3 text-center text-emerald-600 font-medium">2,950</td>
                                        <td class="px-5 py-3 text-center text-amber-600">720</td>
                                        <td class="px-5 py-3 text-center text-red-600">220</td>
                                        <td class="px-5 py-3 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500" style="width: 76%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-700">76%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 font-medium text-slate-800">Distrik Toba</td>
                                        <td class="px-5 py-3 text-center text-slate-600">3,450</td>
                                        <td class="px-5 py-3 text-center text-emerald-600 font-medium">2,680</td>
                                        <td class="px-5 py-3 text-center text-amber-600">580</td>
                                        <td class="px-5 py-3 text-center text-red-600">190</td>
                                        <td class="px-5 py-3 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500" style="width: 78%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-700">78%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 font-medium text-slate-800">Distrik Humbang</td>
                                        <td class="px-5 py-3 text-center text-slate-600">3,120</td>
                                        <td class="px-5 py-3 text-center text-emerald-600 font-medium">2,340</td>
                                        <td class="px-5 py-3 text-center text-amber-600">560</td>
                                        <td class="px-5 py-3 text-center text-red-600">220</td>
                                        <td class="px-5 py-3 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500" style="width: 75%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-700">75%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 font-medium text-slate-800">Distrik Samosir</td>
                                        <td class="px-5 py-3 text-center text-slate-600">2,933</td>
                                        <td class="px-5 py-3 text-center text-emerald-600 font-medium">2,200</td>
                                        <td class="px-5 py-3 text-center text-amber-600">533</td>
                                        <td class="px-5 py-3 text-center text-red-600">200</td>
                                        <td class="px-5 py-3 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500" style="width: 75%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-700">75%</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function dashboardData() {
        return {
            openFullDetail: false,
        }
    }
    </script>
</x-modern-layout>
