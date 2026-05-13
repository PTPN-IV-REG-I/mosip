<x-modern-layout title="Database Rumah" subtitle="Kondisi Rumah Regional 1">
    @php
        $data = \App\Services\GoogleSheetsService::getTekpolData();
        $matrixRows = $data['matrixRows'] ?? [];
        $grandTotal = $data['grandTotal'] ?? 0;
        $cachedAt = $data['cachedAt'] ?? null;
        $fetchError = $data['error'] ?? null;

        $grandTotals = array_fill(0, 16, 0);
        $grandTotalUnits = 0;
        $totalDistrik = 0; 
        $totalKebun = 0; 
        $totalPKS = 0;

        foreach ($matrixRows as $section) {
            // Include data kantor distrik
            foreach ($section['distrik'] as $idx => $v) {
                $grandTotals[$idx] += $v;
            }
            $grandTotalUnits += $section['distrikTotal'];
            $totalDistrik += $section['distrikTotal'];

            foreach ($section['rows'] as [$unit, $values, $total]) {
                foreach ($values as $idx => $v) {
                    $grandTotals[$idx] += $v;
                }
                $grandTotalUnits += $total;

                if (str_contains(strtoupper($unit), 'KEBUN')) {
                    $totalKebun += $total;
                } elseif (str_contains(strtoupper($unit), 'PKS')) {
                    $totalPKS += $total;
                } else {
                    $totalKebun += $total; // fallback
                }
            }
        }

        $permDihuni = array_sum(array_slice($grandTotals, 0, 4));
        $permTdk = array_sum(array_slice($grandTotals, 4, 4));
        $semiDihuni = array_sum(array_slice($grandTotals, 8, 4));
        $semiTdk = array_sum(array_slice($grandTotals, 12, 4));
        $totalPintu = $grandTotal ?: $grandTotalUnits;

        $summaryCards = [
            ['title' => 'Total Pintu',         'value' => number_format($totalPintu),            'sub' => 'Seluruh Regional 1',   'color' => 'blue',   'icon' => 'home', 'number' => $totalPintu],
            ['title' => 'Permanen Dihuni',      'value' => number_format($permDihuni),            'sub' => 'Baik + Rusak',          'color' => 'green',  'icon' => 'building', 'number' => $permDihuni],
            ['title' => 'Semi Permanen Dihuni', 'value' => number_format($semiDihuni),            'sub' => 'Baik + Rusak',          'color' => 'purple', 'icon' => 'clipboard', 'number' => $semiDihuni],
            ['title' => 'Tidak Dihuni',         'value' => number_format($permTdk + $semiTdk),   'sub' => 'Perm. + Semi Perm.',   'color' => 'orange', 'icon' => 'warning', 'number' => $permTdk + $semiTdk],
        ];

        $subHeaders = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Sedang'];
    @endphp

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const baseOpts = {
            chart: { toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 700 } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
            tooltip: { theme: 'light' },
        };

        const labels = {!! json_encode(array_column($matrixRows, 'region')) !!};
        const seriesBaik = [];
        const seriesRusakRingan = [];
        const seriesRusakBerat = [];

        @foreach($matrixRows as $section)
            @php
                $baik = 0; $rusakRingan = 0; $rusakBerat = 0;
                
                // Kantor Distrik
                $d = $section['distrik'];
                $baik += $d[0] + $d[4] + $d[8] + $d[12];
                $rusakRingan += $d[1] + $d[5] + $d[9] + $d[13];
                $rusakBerat += $d[2] + $d[3] + $d[6] + $d[7] + $d[10] + $d[11] + $d[14] + $d[15];

                // Kebun & PKS
                foreach ($section['rows'] as [$u, $vals, $t]) {
                    $baik += $vals[0] + $vals[4] + $vals[8] + $vals[12];
                    $rusakRingan += $vals[1] + $vals[5] + $vals[9] + $vals[13];
                    $rusakBerat += $vals[2] + $vals[3] + $vals[6] + $vals[7] + $vals[10] + $vals[11] + $vals[14] + $vals[15];
                }
            @endphp
            seriesBaik.push({{ $baik }});
            seriesRusakRingan.push({{ $rusakRingan }});
            seriesRusakBerat.push({{ $rusakBerat }});
        @endforeach

        new ApexCharts(document.getElementById('chart-distrik'), {
            ...baseOpts,
            chart: { ...baseOpts.chart, type:'bar', height: 320, stacked: true },
            series: [
                { name: 'Baik', data: seriesBaik },
                { name: 'Rusak Ringan', data: seriesRusakRingan },
                { name: 'Rusak Berat', data: seriesRusakBerat }
            ],
            xaxis: { categories: labels, labels:{ style:{ fontSize:'11px', colors:'#94a3b8' } } },
            yaxis: { labels: { style: { fontSize:'11px', colors:'#94a3b8' } } },
            colors: ['#10b981','#f59e0b','#ef4444'],
            plotOptions: { bar: { borderRadius: 6, columnWidth:'60%' } },
            dataLabels: { enabled: false },
            legend: { position:'top', horizontalAlign:'right', labels:{ colors:'#64748b' }, fontSize:'12px' },
        }).render();

        new ApexCharts(document.getElementById('chart-pie'), {
            ...baseOpts,
            chart: { ...baseOpts.chart, type:'donut', height: 300 },
            series: [{{ $permDihuni }}, {{ $semiDihuni }}, {{ $permTdk + $semiTdk }}],
            labels: ['Permanen Dihuni','Semi Permanen Dihuni','Tidak Dihuni'],
            colors: ['#10b981','#8b5cf6','#f59e0b'],
            plotOptions: { pie: { donut: { size:'65%', labels: { show:true, total: { show:true, label:'Total Pintu', fontSize:'14px', color:'#64748b', formatter:()=>'{{ number_format($totalPintu) }}' } } } } },
            legend: { position:'bottom', labels:{ colors:'#64748b' }, fontSize:'12px' },
            dataLabels: { enabled:false },
        }).render();

        new ApexCharts(document.getElementById('chart-unit-types'), {
            ...baseOpts,
            chart: { ...baseOpts.chart, type:'donut', height: 300 },
            series: [{{ $totalDistrik }}, {{ $totalKebun }}, {{ $totalPKS }}],
            labels: ['Kantor Distrik', 'Kebun', 'PKS'],
            colors: ['#3b82f6','#10b981','#f59e0b'],
            plotOptions: { pie: { donut: { size:'65%', labels: { show:true, total: { show:true, label:'Total Pintu', fontSize:'14px', color:'#64748b', formatter:()=>'{{ number_format($totalDistrik + $totalKebun + $totalPKS) }}' } } } } },
            legend: { position:'bottom', labels:{ colors:'#64748b' }, fontSize:'12px' },
            dataLabels: { enabled:false },
        }).render();
    });
    </script>
    @endpush

    <div x-data="{ search: '' }">
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider border border-indigo-100">Database Kondisi Rumah</span>
                        @if($cachedAt)
                            <span class="text-[10px] text-slate-400 font-medium">Terakhir Sinkron: {{ $cachedAt }}</span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rekap Jumlah Pintu</h1>
                    <p class="mt-1 text-slate-500 text-sm">Monitoring kondisi aset bangunan di seluruh Regional 1 secara real-time.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <form action="{{ route('tekpol.refresh') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold text-xs shadow-sm hover:bg-slate-50 hover:border-indigo-300 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8 8 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8 8 0 01-15.357-2m15.357 2H15"/></svg>
                            Refresh
                        </button>
                    </form>
                    <button @click="$dispatch('open-table')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Tabel Lengkap
                    </button>
                </div>
            </div>
            
            @if(session('success'))
                <div class="mt-6 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-medium flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @foreach($summaryCards as $card)
                @php
                    $colorMap = [
                        'blue' => ['bg' => 'bg-blue-50', 'icon' => 'bg-blue-500', 'text' => 'text-blue-600', 'border' => 'border-blue-100'],
                        'green' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
                        'purple' => ['bg' => 'bg-indigo-50', 'icon' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'border' => 'border-indigo-100'],
                        'orange' => ['bg' => 'bg-amber-50', 'icon' => 'bg-amber-500', 'text' => 'text-amber-600', 'border' => 'border-amber-100'],
                    ];
                    $c = $colorMap[$card['color']];
                @endphp
                <div class="relative overflow-hidden rounded-3xl bg-white border border-slate-200 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-slate-200 group">
                    <div class="absolute top-0 right-0 p-4">
                        <div class="w-10 h-10 rounded-xl {{ $c['icon'] }} text-white flex items-center justify-center shadow-lg shadow-{{ $card['color'] }}-200 group-hover:scale-110 transition-transform duration-300">
                            @if($card['icon']==='home')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @elseif($card['icon']==='building')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @elseif($card['icon']==='clipboard')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $card['title'] }}</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $card['value'] }}</h3>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $c['bg'] }} {{ $c['text'] }}">{{ $card['sub'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
            <div class="lg:col-span-6 rounded-3xl bg-white border border-slate-200 p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Kondisi per Regional</h3>
                        <p class="text-sm text-slate-500">Persentase kondisi fisik bangunan.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-[11px] font-bold text-slate-600">Baik</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="text-[11px] font-bold text-slate-600">RR</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-[11px] font-bold text-slate-600">RB</span>
                        </div>
                    </div>
                </div>
                <div id="chart-distrik"></div>
            </div>

            <div class="lg:col-span-3 rounded-3xl bg-white border border-slate-200 p-8">
                <div class="mb-8 text-center">
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Status Hunian</h3>
                    <p class="text-xs text-slate-500">Permanen & Semi Permanen</p>
                </div>
                <div id="chart-pie"></div>
            </div>

            <div class="lg:col-span-3 rounded-3xl bg-white border border-slate-200 p-8">
                <div class="mb-8 text-center">
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Aset Per Unit</h3>
                    <p class="text-xs text-slate-500">Distrik, Kebun, & PKS</p>
                </div>
                <div id="chart-unit-types"></div>
            </div>
        </div>

        <div class="space-y-12">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Rincian Kondisi Aset</h2>
                <p class="text-slate-500">Informasi detail kondisi pintu untuk setiap unit kerja di bawah Regional 1.</p>
            </div>

            @foreach($matrixRows as $index => $section)
                <div class="relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-px flex-1 bg-slate-200"></div>
                        <h4 class="text-sm font-black text-indigo-600 uppercase tracking-[0.2em] flex items-center gap-2">
                            Regional: {{ $section['region'] }}
                        </h4>
                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @php
                            $allUnits = [];
                            $allUnits[] = ['name' => $section['region'] . ' (Kantor)', 'type' => 'Distrik', 'values' => $section['distrik'], 'total' => $section['distrikTotal']];
                            foreach ($section['rows'] as [$u, $vals, $t]) {
                                $type = str_contains(strtoupper($u), 'KEBUN') ? 'Kebun' : (str_contains(strtoupper($u), 'PKS') ? 'PKS' : 'Lainnya');
                                $allUnits[] = ['name' => $u, 'type' => $type, 'values' => $vals, 'total' => $t];
                            }
                        @endphp

                        @foreach($allUnits as $unit)
                            @php
                                $vals = $unit['values'];
                                $tot = $unit['total'];
                                $baik  = $vals[0] + $vals[4] + $vals[8] + $vals[12];
                                $rusak = $tot - $baik;
                                $pctBaik = $tot > 0 ? round($baik / $tot * 100) : 0;
                                $statusColor = $pctBaik >= 75 ? 'emerald' : ($pctBaik >= 50 ? 'amber' : 'red');
                            @endphp
                            
                            <div class="group bg-white rounded-3xl border border-slate-200 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-slate-200">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="w-10 h-10 flex items-center justify-center grayscale group-hover:grayscale-0 transition-all duration-500">
                                        @if($unit['type'] == 'Distrik')
                                            <img src="{{ asset('storage/images/distrik.png') }}" alt="Distrik" class="w-full h-full object-contain">
                                        @elseif($unit['type'] == 'Kebun')
                                            <img src="{{ asset('storage/images/kebun.png') }}" alt="Kebun" class="w-full h-full object-contain">
                                        @else
                                            <img src="{{ asset('storage/images/pks.png') }}" alt="PKS" class="w-full h-full object-contain">
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-indigo-600 transition-colors">{{ $unit['type'] }}</span>
                                </div>
                                
                                <h4 class="font-bold text-slate-900 mb-1 leading-tight line-clamp-2 h-10">{{ $unit['name'] }}</h4>
                                <div class="flex items-baseline gap-1 mb-4">
                                    <span class="text-2xl font-black text-slate-900 tracking-tighter">{{ number_format($tot) }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pintu</span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                                        <span class="text-slate-400">Kondisi Baik</span>
                                        <span class="text-{{ $statusColor }}-600">{{ $pctBaik }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-{{ $statusColor }}-500 rounded-full transition-all duration-1000" style="width: {{ $pctBaik }}%"></div>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-slate-50">
                                        <div class="flex items-center gap-1">
                                            <span class="text-[11px] font-bold text-slate-900">{{ number_format($baik) }}</span>
                                            <span class="text-[9px] font-medium text-slate-400">Baik</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[11px] font-bold text-slate-900">{{ number_format($rusak) }}</span>
                                            <span class="text-[9px] font-medium text-slate-400">Rusak</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div x-data="{ showTableModal: false }" 
             @open-table.window="showTableModal = true"
             @close-table.window="showTableModal = false"
             x-show="showTableModal" 
             x-cloak 
             class="fixed inset-0 z-[200] flex items-center justify-center">
            {{-- Overlay --}}
            <div x-show="showTableModal" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="showTableModal = false; $dispatch('close-table')"
                 class="absolute inset-0 bg-slate-900"></div>

            {{-- Fullscreen Container --}}
            <div x-show="showTableModal" 
                 x-transition:enter="transition ease-out duration-500" 
                 x-transition:enter-start="opacity-0 translate-y-full" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-300" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 translate-y-full"
                 class="relative w-full h-full bg-white shadow-2xl overflow-hidden flex flex-col">
                
                {{-- Integrated Header --}}
                <div class="flex items-center justify-between px-20 py-8 bg-white z-[110] border-b border-slate-100">
                    <div class="flex items-center gap-12">
                        <div>
                            <h2 class="text-4xl font-black text-slate-900 tracking-tight italic">Database <span class="text-indigo-600 not-italic">Aset</span></h2>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.4em] mt-3">Regional 1 · Laporan Kondisi Pintu</p>
                        </div>
                        
                        {{-- Integrated Search Bar --}}
                        <div class="hidden md:flex items-center gap-5 px-8 py-4 rounded-[2.5rem] bg-slate-50 border border-slate-200 focus-within:border-indigo-400 focus-within:ring-[12px] focus-within:ring-indigo-50/50 transition-all w-[32rem] group shadow-inner">
                            <svg class="w-6 h-6 text-slate-300 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="search" placeholder="Cari unit, kebun, atau wilayah..." class="text-base bg-transparent border-none outline-none w-full text-slate-900 placeholder-slate-400 font-bold tracking-tight">
                        </div>
                    </div>

                    <div class="flex items-center gap-10">
                        <button @click="showTableModal = false; $dispatch('close-table')" class="group flex items-center gap-4 px-10 py-5 rounded-3xl bg-slate-900 text-white hover:bg-red-600 transition-all duration-500 font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-slate-300 hover:shadow-red-200 hover:-translate-y-1">
                            <span>Tutup Laporan</span>
                            <div class="w-6 h-6 flex items-center justify-center bg-white/10 rounded-xl group-hover:bg-white/20 transition-colors">
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Table Content --}}
                <div class="flex-1 overflow-auto bg-slate-50/50 p-20 pt-2">
                    <div class="max-w-[1600px] mx-auto bg-white rounded-[4rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                        <table class="w-full text-sm border-collapse border-2 border-slate-200">
                            <thead class="sticky top-0 z-40">
                                <tr class="bg-slate-50 text-slate-800 border-b-2 border-slate-200">
                                    <th class="sticky left-0 z-50 bg-slate-50 px-8 py-8 text-left font-black uppercase tracking-[0.2em] text-[10px] border-r-2 border-slate-200" rowspan="3">Unit / Wilayah</th>
                                    <th class="px-8 py-5 text-center font-black uppercase tracking-[0.2em] text-[10px] border-r-2 border-slate-200" colspan="8">Bangunan Permanen</th>
                                    <th class="px-8 py-5 text-center font-black uppercase tracking-[0.2em] text-[10px] border-r-2 border-slate-200" colspan="8">Bangunan Semi Permanen</th>
                                    <th class="sticky right-0 z-50 bg-slate-50 px-8 py-8 text-center font-black uppercase tracking-[0.2em] text-[10px]" rowspan="3">Total</th>
                                </tr>
                                <tr class="bg-white text-slate-500 border-b-2 border-slate-200">
                                    <th class="px-8 py-4 text-center font-bold text-[7px] uppercase tracking-widest border-r-2 border-slate-200" colspan="4">Kondisi Bangunan Dihuni</th>
                                    <th class="px-8 py-4 text-center font-bold text-[7px] uppercase tracking-widest border-r-2 border-slate-200" colspan="4">Kondisi Bangunan Kosong</th>
                                    <th class="px-8 py-4 text-center font-bold text-[7px] uppercase tracking-widest border-r-2 border-slate-200" colspan="4">Kondisi Bangunan Dihuni</th>
                                    <th class="px-8 py-4 text-center font-bold text-[7px] uppercase tracking-widest border-r-2 border-slate-200" colspan="4">Kondisi Bangunan Kosong</th>
                                </tr>
                                <tr class="bg-white text-slate-500">
                                    @for($g = 0; $g < 4; $g++)
                                        @foreach(['Baik', 'Rusak Ringan', 'Rusak Berat', 'Rusak Sedang'] as $sh)
                                            @php
                                                $colorClass = match($sh) {
                                                    'Baik' => 'text-emerald-700',
                                                    'Rusak Ringan'   => 'text-amber-700',
                                                    'Rusak Berat'   => 'text-rose-700',
                                                    'Rusak Sedang'   => 'text-slate-500',
                                                    default => ''
                                                };
                                            @endphp
                                            <th class="px-1 py-4 text-center text-[6px] font-black border-b-2 border-slate-200 border-r-2 border-slate-100 uppercase tracking-tighter {{ $colorClass }} leading-tight">{{ $sh }}</th>
                                        @endforeach
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-100">
                                @foreach($matrixRows as $section)
                                    <tr class="bg-indigo-50/20 group/reg">
                                        <td class="sticky left-0 z-20 bg-indigo-50/80 backdrop-blur-md px-8 py-6 font-black text-indigo-700 border-r-2 border-indigo-200 italic tracking-tight">Regional {{ $section['region'] }}</td>
                                        @foreach($section['distrik'] as $val)
                                            <td class="px-4 py-6 text-center font-black text-slate-400 border-r-2 border-slate-100 group-hover/reg:text-indigo-700 transition-colors">{{ $val > 0 ? number_format($val) : '—' }}</td>
                                        @endforeach
                                        <td class="sticky right-0 z-20 bg-indigo-50/80 backdrop-blur-md px-8 py-6 text-center font-black text-indigo-700 border-l-2 border-indigo-200">{{ number_format($section['distrikTotal']) }}</td>
                                    </tr>

                                    @foreach($section['rows'] as [$unit, $values, $total])
                                        <tr class="hover:bg-slate-50 transition-all duration-300 group border-b-2 border-slate-100"
                                            x-show="!search || '{{ strtolower($unit . ' ' . $section['region']) }}'.includes(search.toLowerCase())">
                                            <td class="sticky left-0 z-20 bg-white px-8 py-5 font-bold text-slate-800 border-r-2 border-slate-100 group-hover:bg-slate-50 transition-colors">{{ $unit }}</td>
                                            @foreach($values as $i => $val)
                                                @php 
                                                    $isRusak = in_array($i, [2, 3, 6, 7, 10, 11, 14, 15]); 
                                                    $isBaik = in_array($i, [0, 4, 8, 12]);
                                                @endphp
                                                <td class="px-4 py-5 text-center border-r-2 border-slate-50 {{ $val > 0 ? ($isRusak ? 'text-rose-600 font-black' : ($isBaik ? 'text-emerald-600 font-bold' : 'text-slate-700 font-medium')) : 'text-slate-200' }}">
                                                    {{ $val > 0 ? number_format($val) : '—' }}
                                                </td>
                                            @endforeach
                                            <td class="sticky right-0 z-20 bg-white px-8 py-5 text-center font-black text-slate-900 border-l-2 border-slate-100 group-hover:bg-slate-50 transition-colors">{{ number_format($total) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot class="sticky bottom-0 z-40">
                                <tr class="bg-slate-900 text-white border-t-2 border-slate-800">
                                    <td class="sticky left-0 z-50 bg-slate-900 px-8 py-10 font-black uppercase tracking-[0.2em] text-[11px] border-r-2 border-white/10">Total Keseluruhan</td>
                                    @foreach($grandTotals as $val)
                                        <td class="px-4 py-10 text-center font-black text-sm border-r-2 border-white/10">{{ number_format($val) }}</td>
                                    @endforeach
                                    <td class="sticky right-0 z-50 bg-slate-900 px-8 py-10 text-center font-black text-2xl text-indigo-400 border-l-2 border-white/10">{{ number_format($grandTotalUnits) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    </script>
    @endpush
</x-modern-layout>
