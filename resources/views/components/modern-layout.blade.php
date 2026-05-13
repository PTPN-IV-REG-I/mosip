@props(['title' => '', 'subtitle' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MOSIP – Monitoring Operasional Sipil & Perumahan">
    <title>{{ $title ?? 'Dashboard' }} – MOSIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%234f46e5'/><text x='50%25' y='55%25' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='Inter' font-weight='bold' fill='white'>M</text></svg>">
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes pageIn {
            0% { opacity: 0; transform: scale(0.98) translateY(10px); filter: blur(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        }

        @keyframes contentSlideUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-page-in {
            animation: pageIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .animate-content-fade {
            animation: contentSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: 0.2s;
            opacity: 0;
        }

        .fab-glow {
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.4);
        }
        
        .fab-glow:hover {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.6);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 antialiased overflow-x-hidden" 
      x-data="modernApp()"
      x-init="init()"
      @open-table.window="showTableModal = true"
      :class="{ 'overflow-hidden': showTableModal }">

    <div x-show="mounted" 
         class="animate-page-in">
        
        <header class="relative z-20 sticky top-0 bg-white/80 backdrop-blur-xl border-b border-slate-200 shadow-sm"
                x-data="{ hidden: false }"
                @open-table.window="hidden = true"
                @close-table.window="hidden = false"
                :class="{ 'hidden': hidden }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center shadow-lg shadow-slate-200">
                            <span class="text-white font-black text-lg">M</span>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-black text-slate-900 tracking-tight">MOSIP</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Regional 1 Dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-100/50 border border-slate-200/60">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari..." class="text-xs bg-transparent border-none outline-none w-32 text-slate-700 placeholder-slate-400 font-medium">
                    </div>

                    <button @click="showNotifications = !showNotifications" class="relative p-2.5 rounded-2xl hover:bg-slate-100 transition-all duration-200">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-indigo-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-2xl hover:bg-slate-100 transition-all duration-200">
                            <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white text-xs font-black">
                                @auth {{ strtoupper(substr(Auth::user()->name, 0, 1)) }} @else U @endauth
                            </div>
                            <div class="hidden sm:block text-left pr-2">
                                @auth
                                    <p class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                                @else
                                    <p class="text-xs font-bold text-slate-900">Guest</p>
                                @endauth
                            </div>
                        </button>
                        {{-- Dropdown tetap ada namun lebih minimalis --}}
                        <div x-show="open" x-cloak @click.outside="open = false"
                             class="absolute right-0 top-full mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl z-50">
                            @auth
                                <div class="px-4 py-3 border-b border-slate-50 bg-slate-50/50">
                                    <p class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profil
                                    </a>
                                    @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Super Admin')
                                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            Kelola User
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-50 mt-1">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="relative z-10 animate-content-fade">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {{ $slot }}
        </div>
    </main>

    </div> {{-- End of mounted div --}}

    {{-- Floating Navigation (FAB) Toggle Style --}}
    <div class="fixed bottom-10 right-10 z-[90]">
        @php
            $isTekpol = request()->routeIs('tekpol.*');
            $isAdmin  = request()->routeIs('admin.*') || request()->routeIs('users.*');
            $isHome   = request()->routeIs('dashboard');

            // Determine target
            if ($isTekpol) {
                $targetUrl = route('dashboard');
                $targetLabel = 'Overview';
                $targetIcon = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>';
                $targetColor = 'bg-slate-900 shadow-slate-900/40';
            } else {
                $targetUrl = route('tekpol.dashboard');
                $targetLabel = 'Database Rumah';
                $targetIcon = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>';
                $targetColor = 'bg-indigo-600 shadow-indigo-600/40';
            }
        @endphp

        <div class="flex flex-col items-end gap-4" x-data="{ showLabel: false }">
            @auth
                @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Super Admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-12 h-12 rounded-full bg-white border border-slate-200 shadow-xl flex items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-200 transition-all hover:scale-110 mb-2"
                       @mouseenter="showLabel = 'admin'" @mouseleave="showLabel = false">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 001.066-2.573c1.543.94-3.31-.826 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                @endif
            @endauth

            <a href="{{ $targetUrl }}" 
               class="group relative flex items-center justify-center w-20 h-20 rounded-full {{ $targetColor }} shadow-2xl transition-all duration-500 hover:scale-110 active:scale-95 fab-glow"
               @mouseenter="showLabel = 'target'" @mouseleave="showLabel = false">
                
                <div class="text-white transition-transform duration-500 group-hover:rotate-12">
                    {!! $targetIcon !!}
                </div>

                <div x-show="showLabel === 'target'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="absolute right-full mr-6 px-4 py-2 rounded-2xl bg-slate-900 text-white text-xs font-black uppercase tracking-widest whitespace-nowrap shadow-2xl border border-white/10">
                    {{ $targetLabel }}
                    <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 border-r border-t border-white/10"></div>
                </div>
            </a>
        </div>
    </div>

    <div x-show="showNotifications" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         class="fixed top-20 right-4 z-50 w-80">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden p-2">
            <div class="px-4 py-3 border-b border-slate-50">
                <p class="text-xs font-black uppercase tracking-widest text-slate-900">Notifikasi</p>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <div class="p-3 rounded-2xl hover:bg-slate-50 transition-colors">
                    <p class="text-xs font-bold text-slate-700">Data berhasil diperbarui</p>
                    <p class="text-[10px] text-slate-400">5 menit lalu</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modernApp', () => ({
            showNotifications: false,
            mounted: false,
            init() {
                this.$nextTick(() => {
                    setTimeout(() => { this.mounted = true }, 100);
                });

                window.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.showNotifications = false;
                        window.dispatchEvent(new CustomEvent('close-table'));
                    }
                });
            }
        }));
    });
    </script>
    @stack('scripts')
</body>
</html>
