<x-app-layout>
    <x-slot name="title">Tekpol Dashboard</x-slot>
    <x-slot name="subtitle">Database Kondisi Rumah Regional 1</x-slot>

    @push('styles')
    <style>
    :root {
      --amber-50:#FAEEDA;--amber-100:#FAC775;--amber-400:#BA7517;--amber-600:#854F0B;--amber-800:#633806;
      --gray-50:#F1EFE8;--gray-100:#D3D1C7;--gray-200:#B4B2A9;--gray-400:#888780;--gray-600:#5F5E5A;--gray-800:#444441;
      --teal-50:#E1F5EE;--teal-400:#1D9E75;--teal-600:#0F6E56;--teal-800:#085041;
      --red-50:#FCEBEB;--red-200:#F09595;--red-400:#E24B4A;--red-600:#A32D2D;
      --blue-50:#E6F1FB;--blue-400:#378ADD;--blue-600:#185FA5;--blue-800:#0C447C;
      --coral-50:#FAECE7;--coral-400:#D85A30;--coral-600:#993C1D;
    }
    
    .tekpol-dash * { box-sizing:border-box; margin:0; padding:0; }
    .tekpol-dash { font-family:var(--font-sans,system-ui,sans-serif); padding-bottom: 2rem; max-width: 1000px; width:100%; margin: 0 auto;}

    /* ── Header ── */
    .tekpol-dash .header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}
    .tekpol-dash .badge-pill{display:inline-flex;align-items:center;gap:6px;background:var(--amber-50);color:var(--amber-600);border:0.5px solid var(--amber-100);border-radius:999px;padding:4px 12px;font-size:11px;font-weight:500;margin-bottom:8px}
    .tekpol-dash .badge-dot{width:7px;height:7px;border-radius:50%;background:var(--amber-400)}
    .tekpol-dash .hd-title{font-size:22px;font-weight:500;color:var(--gray-800)}
    .tekpol-dash .hd-sub{font-size:12px;color:var(--gray-400);margin-top:3px}
    .tekpol-dash .hd-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px}
    .tekpol-dash .btn-refresh{display:inline-flex;align-items:center;gap:6px;background:#fff;border:0.5px solid var(--gray-200);border-radius:8px;padding:8px 14px;font-size:12px;font-weight:500;color:var(--gray-800);cursor:pointer;transition:background .15s}
    .tekpol-dash .btn-refresh:hover{background:var(--gray-50)}
    .tekpol-dash .live-badge{display:flex;align-items:center;gap:8px;background:var(--teal-50);border:0.5px solid #9FE1CB;border-radius:8px;padding:6px 10px}
    .tekpol-dash .live-dot{width:6px;height:6px;border-radius:50%;background:var(--teal-400);animation:pulse 1.8s ease-in-out infinite}
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
    .tekpol-dash .live-text{font-size:11px;font-weight:500;color:var(--teal-600)}
    .tekpol-dash .live-sub{font-size:10px;color:var(--teal-400)}

    /* ── Alerts ── */
    .tekpol-dash .alert { border-radius: 8px; padding: 12px 16px; margin-bottom: 24px; font-size: 13px; }
    .tekpol-dash .alert-success { background: var(--teal-50); border: 1px solid #9FE1CB; color: var(--teal-800); }
    .tekpol-dash .alert-error { background: var(--red-50); border: 1px solid var(--red-200); color: var(--red-800); }
    .tekpol-dash .alert-error a { color: var(--red-600); font-weight: bold; text-decoration: underline; }

    /* ── Summary Cards ── */
    .tekpol-dash .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:24px}
    .tekpol-dash .card{background:#fff;border:0.5px solid var(--gray-100);border-radius:12px;padding:14px 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.02)}
    .tekpol-dash .card-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
    .tekpol-dash .ic-amber{background:var(--amber-50)}
    .tekpol-dash .ic-teal{background:var(--teal-50)}
    .tekpol-dash .ic-blue{background:var(--blue-50)}
    .tekpol-dash .ic-red{background:var(--red-50)}
    .tekpol-dash .card-label{font-size:10px;font-weight:500;color:var(--gray-400);text-transform:uppercase;letter-spacing:.08em}
    .tekpol-dash .card-val{font-size:22px;font-weight:500;color:var(--gray-800);margin-top:2px}
    .tekpol-dash .card-sub{font-size:10px;color:var(--gray-400);margin-top:1px}

    /* ── Filter Bar ── */
    .tekpol-dash .filter-bar{background:#fff;border:0.5px solid var(--gray-100);border-radius:12px;padding:14px 16px;margin-bottom:16px; box-shadow: 0 1px 2px rgba(0,0,0,0.02)}
    .tekpol-dash .filter-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .tekpol-dash .search-wrap{position:relative;flex:1;min-width:160px}
    .tekpol-dash .search-wrap input{width:100%;padding:7px 10px 7px 32px;font-size:12px;border:0.5px solid var(--gray-200);border-radius:8px;background:var(--gray-50);color:var(--gray-800);outline:none}
    .tekpol-dash .search-wrap input:focus{border-color:var(--amber-400); background:#fff;}
    .tekpol-dash .search-ico{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:var(--gray-400)}
    .tekpol-dash .filter-select{padding:7px 10px;font-size:12px;border:0.5px solid var(--gray-200);border-radius:8px;background:var(--gray-50);color:var(--gray-800);outline:none;cursor:pointer}
    .tekpol-dash .filter-select:focus{border-color:var(--amber-400); background:#fff;}
    .tekpol-dash .filter-count{margin-left:auto;font-size:11px;color:var(--gray-400)}
    .tekpol-dash .filter-count b{color:var(--gray-800);font-weight:500}

    /* ── Table ── */
    .tekpol-dash .tbl-wrap{overflow-x:auto;border:0.5px solid var(--gray-100);border-radius:12px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); background:#fff;}
    .tekpol-dash table{width:100%;border-collapse:collapse;font-size:11px}
    .tekpol-dash thead tr th{background:var(--gray-50);border-bottom:0.5px solid var(--gray-100);border-right:0.5px solid var(--gray-100);padding:7px 8px;text-align:center;font-weight:500;color:var(--gray-600);white-space:nowrap}
    .tekpol-dash th.sticky-col, .tekpol-dash td.sticky-col{position:sticky;left:0;z-index:5;background:#fff;min-width:140px;max-width:180px;text-align:left;padding-left:12px; border-right:1px solid var(--gray-100);}
    .tekpol-dash thead th.sticky-col{background:var(--gray-50)}
    .tekpol-dash th.main-head{background:var(--amber-600);color:#FAC775;font-weight:500;font-size:11px; border-right-color:var(--amber-800);}
    .tekpol-dash th.main-head.sticky-col{background:var(--amber-600); border-right-color:var(--amber-800);}
    .tekpol-dash th.group-head-dihuni{background:var(--teal-50);color:var(--teal-600); border-right-color: #9FE1CB;}
    .tekpol-dash th.group-head-tidak{background:var(--red-50);color:var(--red-600); border-right-color: var(--red-200);}
    .tekpol-dash th.sub-head{background:var(--gray-50);color:var(--gray-400);font-size:10px;font-weight:400; border-right-color:var(--gray-100);}
    .tekpol-dash td{padding:6px 8px;text-align:center;border-bottom:0.5px solid var(--gray-50); border-right:0.5px solid var(--gray-50);color:var(--gray-800)}
    .tekpol-dash tr:last-child td{border-bottom:none}
    .tekpol-dash .row-section td{background:var(--gray-50);color:var(--gray-800);font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.05em}
    .tekpol-dash .row-section td.sticky-col{background:var(--gray-50)}
    .tekpol-dash .row-subtotal td{background:var(--amber-50)}
    .tekpol-dash .row-subtotal td.sticky-col{background:var(--amber-50)}
    .tekpol-dash .row-grand td{background:var(--amber-600);color:#FAC775;font-weight:500}
    .tekpol-dash .row-grand td.sticky-col{background:var(--amber-600);color:#FAC775}
    .tekpol-dash .row-unit:hover td{background:var(--gray-50)}
    .tekpol-dash .row-unit:hover td.sticky-col{background:var(--gray-50)}
    .tekpol-dash .c-rusak{color:var(--red-600);font-weight:500}
    .tekpol-dash .c-dash{color:var(--gray-200)}
    .tekpol-dash .c-big{color:var(--blue-600);font-weight:500}

    /* ── Distrik Cards ── */
    .tekpol-dash .distrik-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;margin-top:24px}
    .tekpol-dash .distrik-card{background:#fff;border:0.5px solid var(--gray-100);border-radius:12px;padding:14px 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.02)}
    .tekpol-dash .distrik-card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
    .tekpol-dash .distrik-name{font-size:12px;font-weight:500;color:var(--gray-800)}
    .tekpol-dash .pill-baik{font-size:10px;font-weight:500;padding:3px 8px;border-radius:999px}
    .tekpol-dash .pil-green{background:var(--teal-50);color:var(--teal-600)}
    .tekpol-dash .pil-amber{background:var(--amber-50);color:var(--amber-600)}
    .tekpol-dash .pil-red{background:var(--red-50);color:var(--red-600)}
    .tekpol-dash .distrik-num{font-size:20px;font-weight:500;color:var(--gray-800)}
    .tekpol-dash .distrik-num span{font-size:11px;font-weight:400;color:var(--gray-400)}
    .tekpol-dash .prog-track{height:4px;border-radius:999px;background:var(--gray-50);margin-top:10px;overflow:hidden}
    .tekpol-dash .prog-bar{height:100%;border-radius:999px}
    .tekpol-dash .prog-green{background:var(--teal-400)}
    .tekpol-dash .prog-amber{background:var(--amber-400)}
    .tekpol-dash .prog-red{background:var(--red-400)}
    .tekpol-dash .distrik-footer{display:flex;justify-content:space-between;margin-top:6px;font-size:10px;color:var(--gray-400)}
    .tekpol-dash .distrik-units{margin-top:6px;font-size:10px;color:var(--gray-400)}
    </style>
    @endpush

    <div class="tekpol-dash" x-data="tekpol()">

        <!-- Header -->
        <div class="header">
            <div>
                <div class="badge-pill">
                    <div class="badge-dot"></div>
                    Database Kondisi Rumah – Regional 1
                </div>
                <div class="hd-title">Rekap Jumlah Pintu</div>
                <div class="hd-sub">Data live dari Google Sheets @if($cachedAt) · Diperbarui: {{ $cachedAt }} @endif</div>
            </div>
            <div class="hd-right">
                <form id="refresh-form" action="{{ route('tekpol.refresh') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-refresh" onclick="this.innerHTML='⟳ Memuat...'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8 8 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8 8 0 01-15.357-2m15.357 2H15"/></svg>
                        Refresh Data
                    </button>
                </form>
                <div class="live-badge">
                    <div class="live-dot"></div>
                    <div>
                        <div class="live-text">Data Aktif</div>
                        <div class="live-sub">Sumber: Spreadsheet Regional 1</div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <strong>Sukses:</strong> {{ session('success') }}
            </div>
        @endif

        @if($fetchError)
            <div class="alert alert-error">
                <strong>Gagal mengambil data terbaru:</strong> {{ $fetchError }}<br>
                <span style="font-size: 11px; margin-top: 4px; display: block;">Data yang ditampilkan mungkin tidak akurat. Silakan coba <a href="#" onclick="event.preventDefault(); document.getElementById('refresh-form').submit();">refresh ulang</a>.</span>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="cards">
            @foreach($summaryCards as $card)
                <div class="card">
                    <div class="card-icon 
                        @if($card['color']==='blue') ic-blue 
                        @elseif($card['color']==='green') ic-teal 
                        @elseif($card['color']==='violet') ic-amber 
                        @else ic-red @endif">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            style="color: 
                            @if($card['color']==='blue') var(--blue-600)
                            @elseif($card['color']==='green') var(--teal-600)
                            @elseif($card['color']==='violet') var(--amber-600)
                            @else var(--red-600) @endif">
                            @if($card['icon']==='home')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            @elseif($card['icon']==='building')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            @elseif($card['icon']==='clipboard')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            @endif
                        </svg>
                    </div>
                    <div class="card-label">{{ $card['title'] }}</div>
                    <div class="card-val">{{ $card['value'] }}</div>
                    <div class="card-sub">{{ $card['sub'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-row">
                <div class="search-wrap">
                    <svg class="search-ico" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" x-model="search" placeholder="Cari unit / kebun...">
                </div>
                <select class="filter-select" x-model="filterKat">
                    <option value="">Semua Distrik</option>
                    @foreach($matrixRows as $section)
                        <option value="{{ strtolower($section['region']) }}">{{ $section['region'] }}</option>
                    @endforeach
                </select>
                <select class="filter-select" x-model="filterJenis">
                    <option value="">Semua Jenis</option>
                    <option value="kebun">Kebun</option>
                    <option value="pks">PKS / PKO</option>
                </select>
                <div class="filter-count">Menampilkan <b x-text="visibleCount"></b> unit</div>
            </div>
        </div>

        <!-- Table -->
        <div class="tbl-wrap">
            <table id="main-table">
                <thead>
                    <tr>
                        <th class="sticky-col main-head" rowspan="3">Unit / Distrik</th>
                        <th class="main-head" colspan="8">Permanen</th>
                        <th class="main-head" colspan="8">Semi Permanen</th>
                        <th class="main-head" rowspan="3">Total</th>
                    </tr>
                    <tr>
                        <th class="group-head-dihuni" colspan="4">Dihuni</th>
                        <th class="group-head-tidak" colspan="4">Tidak Dihuni</th>
                        <th class="group-head-dihuni" colspan="4">Dihuni</th>
                        <th class="group-head-tidak" colspan="4">Tidak Dihuni</th>
                    </tr>
                    <tr>
                        @for($g = 0; $g < 4; $g++)
                            @foreach($subHeaders as $sh)
                                <th class="sub-head">{{ $sh === 'Rusak Ringan' ? 'RR' : ($sh === 'Rusak Berat' ? 'RB' : ($sh === 'Sedang' ? 'RS' : 'Baik')) }}</th>
                            @endforeach
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrixRows as $section)
                        <!-- Baris DISTRIK -->
                        <tr class="row-section tekpol-section" data-kat="{{ strtolower($section['region']) }}">
                            <td class="sticky-col">{{ $section['region'] }}</td>
                            @foreach($section['distrik'] as $val)
                                <td class="{{ $val == 0 ? 'c-dash' : '' }}">{{ $val > 0 ? number_format($val) : '-' }}</td>
                            @endforeach
                            <td class="c-big">{{ number_format($section['distrikTotal']) }}</td>
                        </tr>

                        <!-- Baris Unit -->
                        @foreach($section['rows'] as [$unit, $values, $total])
                            <tr class="row-unit tekpol-row"
                                data-search="{{ strtolower($unit . ' ' . $section['region']) }}"
                                data-kat="{{ strtolower($section['region']) }}"
                                data-jenis="{{ str_contains(strtolower($unit), 'pks') || str_contains(strtolower($unit), 'pko') ? 'pks' : 'kebun' }}">
                                <td class="sticky-col">{{ $unit }}</td>
                                @foreach($values as $i => $val)
                                    @php $isRusak = in_array($i, [2, 3, 6, 7, 10, 11, 14, 15]); @endphp
                                    <td class="{{ $val > 0 && $isRusak ? 'c-rusak' : ($val == 0 ? 'c-dash' : '') }}">
                                        {{ $val > 0 ? number_format($val) : '-' }}
                                    </td>
                                @endforeach
                                <td class="{{ $total > 500 ? 'c-big' : '' }}">{{ number_format($total) }}</td>
                            </tr>
                        @endforeach

                        <!-- Baris Subtotal Distrik -->
                        <tr class="row-subtotal tekpol-section" data-kat="{{ strtolower($section['region']) }}">
                            <td class="sticky-col"></td>
                            @foreach($section['subtotal'] as $i => $val)
                                @php $isRusak = in_array($i, [2, 3, 6, 7, 10, 11, 14, 15]); @endphp
                                <td class="{{ $val > 0 && $isRusak ? 'c-rusak' : ($val == 0 ? 'c-dash' : '') }}">
                                    {{ $val > 0 ? number_format($val) : '-' }}
                                </td>
                            @endforeach
                            <td class="c-big">{{ number_format($section['subtotalTotal']) }}</td>
                        </tr>
                    @endforeach

                    <!-- Grand Total -->
                    <tr class="row-grand">
                        <td class="sticky-col">TOTAL SELURUH</td>
                        @foreach($grandTotals as $val)
                            <td>{{ $val > 0 ? number_format($val) : '-' }}</td>
                        @endforeach
                        <td>{{ number_format($grandTotalUnits) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Per-Distrik Cards -->
        <div class="distrik-grid">
            @foreach($matrixRows as $section)
                @php
                    $distrikTotal = array_sum(array_column($section['rows'], 2));
                    $baik = 0; $rusak = 0;
                    foreach ($section['rows'] as [$u, $vals, $t]) {
                        $baik  += $vals[0] + $vals[4] + $vals[8] + $vals[12];
                        $rusak += $vals[1] + $vals[2] + $vals[3] + $vals[5] + $vals[6] + $vals[7]
                                + $vals[9] + $vals[10] + $vals[11] + $vals[13] + $vals[14] + $vals[15];
                    }
                    $pctBaik = $distrikTotal > 0 ? round($baik / $distrikTotal * 100) : 0;
                    
                    $pilClass = $pctBaik >= 70 ? 'pil-green' : ($pctBaik >= 50 ? 'pil-amber' : 'pil-red');
                    $progClass = $pctBaik >= 70 ? 'prog-green' : ($pctBaik >= 50 ? 'prog-amber' : 'prog-red');
                @endphp
                <div class="distrik-card">
                    <div class="distrik-card-head">
                        <div class="distrik-name">{{ $section['region'] }}</div>
                        <div class="pill-baik {{ $pilClass }}">{{ $pctBaik }}% Baik</div>
                    </div>
                    <div class="distrik-num">{{ number_format($distrikTotal) }} <span>pintu</span></div>
                    <div class="prog-track"><div class="prog-bar {{ $progClass }}" style="width:{{ $pctBaik }}%"></div></div>
                    <div class="distrik-footer">
                        <span>{{ number_format($baik) }} Kondisi Baik</span>
                        <span>{{ number_format($rusak) }} Perlu Perhatian</span>
                    </div>
                    <div class="distrik-units">{{ count($section['rows']) }} unit dalam distrik ini</div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
    function tekpol() {
        return {
            search: '',
            filterKat: '',
            filterJenis: '',
            visibleCount: 0,
            init() {
                this.$watch('search',      () => this.applyFilters());
                this.$watch('filterKat',   () => this.applyFilters());
                this.$watch('filterJenis', () => this.applyFilters());
                this.$nextTick(() => this.applyFilters());
            },
            applyFilters() {
                const rows     = document.querySelectorAll('.tekpol-row');
                const sections = document.querySelectorAll('.tekpol-section');
                let count = 0;

                rows.forEach(row => {
                    const matchSearch = !this.search      || row.dataset.search.includes(this.search.toLowerCase());
                    const matchKat    = !this.filterKat   || row.dataset.kat   === this.filterKat;
                    const matchJenis  = !this.filterJenis || row.dataset.jenis === this.filterJenis;
                    const show = matchSearch && matchKat && matchJenis;
                    row.style.display = show ? '' : 'none';
                    if (show) count++;
                });

                // Show/hide section header rows
                sections.forEach(sec => {
                    const kat = sec.dataset.kat;
                    const hasVisible = [...rows].some(r => r.dataset.kat === kat && r.style.display !== 'none');
                    sec.style.display = hasVisible ? '' : 'none';
                });

                this.visibleCount = count;
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
