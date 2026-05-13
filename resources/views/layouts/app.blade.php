<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MOSIP – Monitoring Operasional Sipil &amp; Perumahan">
    <title>{{ $title ?? 'Dashboard' }} – MOSIP</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Heroicons (SVG sprite via unpkg) --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%234f46e5'/><text x='50%' y='55%' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='Inter,sans-serif' font-weight='bold' fill='white'>M</text></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased"
      x-data="appShell()"
      :class="{ 'overflow-hidden': mobileOpen }">

<div class="app-shell">
    <div class="page-orb page-orb-left"></div>
    <div class="page-orb page-orb-right"></div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- SIDEBAR                                                      --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<aside class="sidebar scrollbar-thin"
       :class="{ 'collapsed': sidebarCollapsed, 'mobile-open': mobileOpen }">

    {{-- Logo area --}}
    <div class="relative flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 shadow-lg ring-1 ring-white/15 shrink-0">
            <span class="text-white font-black text-lg leading-none">M</span>
        </div>
        <div class="link-label">
            <p class="text-white font-bold text-base leading-tight tracking-tight">MOSIP</p>
            <p class="text-indigo-200/90 text-xs">Monitoring Operational Sipil &amp; Perumahan</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 py-4 overflow-y-auto scrollbar-thin">
        <p class="px-5 mb-2 text-indigo-200/55 text-[10px] font-semibold uppercase tracking-[0.24em] link-label">Menu Utama</p>

        @auth
            @if(auth()->user()->role === 'Tekpol')
                <x-sidebar-link href="{{ route('tekpol.dashboard') }}" icon="table-cells" label="Dashboard Tekpol"
                    :active="request()->routeIs('tekpol.*')" />
            @elseif(auth()->user()->role === 'Admin')
                <x-sidebar-link href="{{ route('dashboard') }}" icon="home" label="Dashboard"
                    :active="request()->routeIs('dashboard')" />
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="chart-bar" label="Admin Dashboard"
                    :active="request()->routeIs('admin.*')" />
                <x-sidebar-link href="{{ route('tekpol.dashboard') }}" icon="table-cells" label="Tekpol"
                    :active="request()->routeIs('tekpol.*')" />
                <x-sidebar-link href="{{ route('users.index') }}" icon="users" label="User Management"
                    :active="request()->routeIs('users.*')" />
            @else
                <x-sidebar-link href="{{ route('dashboard') }}" icon="home" label="Dashboard"
                    :active="request()->routeIs('dashboard')" />
            @endif
        @else
            <x-sidebar-link href="{{ route('dashboard') }}" icon="home" label="Dashboard"
                :active="request()->routeIs('dashboard')" />
        @endauth

        <p class="px-5 mt-4 mb-2 text-indigo-200/55 text-[10px] font-semibold uppercase tracking-[0.24em] link-label">Pengaturan</p>
        <x-sidebar-link href="#" icon="cog" label="Konfigurasi" />
        <x-sidebar-link href="#" icon="bell" label="Notifikasi" />
    </nav>

    <div class="mx-4 mb-4 rounded-2xl border border-white/10 bg-white/10 p-3 text-white/90 link-label">
        <div class="flex items-center gap-3">
            <div class="relative flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-400/15 text-emerald-200">
                <span class="absolute inset-0 rounded-2xl bg-emerald-400/10 animate-pulse-soft"></span>
                <svg class="relative w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.586-3.414A2 2 0 0019.172 5H4.828a2 2 0 00-1.414.586l-1.414 1.414A2 2 0 001 8.414V10a2 2 0 002 2h18a2 2 0 002-2V8.414a2 2 0 00-.586-1.414l-1.414-1.414z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold">Sinkronisasi Stabil</p>
                <p class="text-[11px] text-indigo-100/75">Update terakhir {{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    {{-- Collapse toggle (desktop) --}}
    <div class="hidden md:flex items-center justify-end px-3 py-3 border-t border-white/10">
        <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="flex h-11 w-11 items-center justify-center rounded-2xl text-indigo-100/80 hover:bg-white/15 hover:text-white transition-all duration-200"
                title="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-300"
                 :class="sidebarCollapsed ? 'rotate-180' : ''"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MOBILE OVERLAY                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div x-show="mobileOpen" x-cloak
     @click="mobileOpen = false"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/50 z-30 md:hidden"></div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TOPBAR                                                       --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<header class="topbar"
        :style="sidebarCollapsed ? 'left:104px' : 'left:296px'"
        style="left:296px">

    {{-- Left: hamburger (mobile) + breadcrumb --}}
    <div class="flex items-center gap-3 min-w-0">
        {{-- Mobile hamburger --}}
        <button @click="mobileOpen = !mobileOpen"
                class="md:hidden btn-icon">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400 hidden sm:block">MOSIP Workspace</p>
            <h1 class="truncate text-base font-semibold text-slate-800 leading-tight">{{ $title ?? 'Dashboard' }}</h1>
            <p class="truncate text-xs text-slate-400 hidden sm:block">{{ $subtitle ?? 'MOSIP – Monitoring Operasional Sipil & Perumahan' }}</p>
        </div>
    </div>

    {{-- Right: search, notif, profile --}}
    <div class="flex items-center gap-2">
        {{-- Search --}}
        <div class="relative hidden sm:block">
            <input type="text" placeholder="Cari..."
                   class="form-input !py-2.5 !pl-9 !pr-4 w-44 lg:w-64 text-xs !bg-white/80">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="hidden lg:flex items-center gap-2 rounded-2xl border border-slate-200/80 bg-white/70 px-3 py-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white text-[10px] font-bold">CO</div>
            <div class="leading-tight">
                <p class="text-[11px] font-semibold text-slate-700">Company Logo</p>
                <p class="text-[10px] text-slate-400">Placeholder siap ganti logo asli</p>
            </div>
        </div>

        {{-- Notification --}}
        <button class="btn-icon relative">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </button>

        {{-- Profile dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-2xl hover:bg-slate-100 transition-all duration-150">
                <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-primary-500 to-purple-600
                            flex items-center justify-center text-white text-xs font-bold shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-xs font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400">{{ Auth::user()->role }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                     :class="open ? 'rotate-180' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                 class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-semibold text-slate-800">Admin User</p>
                    <p class="text-xs text-slate-400">admin@mosip.go.id</p>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profil Saya
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pengaturan
                    </a>
                </div>
                <div class="border-t border-slate-100 py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT                                                 --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<main id="main-content"
      class="page-content"
      :class="{ 'sidebar-collapsed': sidebarCollapsed }">
    <div class="px-4 pb-8 pt-2 sm:px-6 lg:px-8 animate-fade-in">
        {{ $slot }}
    </div>
</main>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ALPINE APP SHELL                                             --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<script>
function appShell() {
    return {
        sidebarCollapsed: false,
        mobileOpen: false,
    }
}
</script>
@stack('scripts')
</div>
</body>
</html>
