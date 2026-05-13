<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Login – MOSIP Monitoring Operasional Sipil & Perumahan">
    <title>Login – MOSIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">

<div class="login-bg flex items-center justify-center relative overflow-hidden" style="min-height:100vh">

    {{-- Decorative blobs --}}
    <div class="absolute top-[-120px] left-[-120px] w-96 h-96 rounded-full
                bg-white/5 blur-3xl pointer-events-none animate-float"></div>
    <div class="absolute bottom-[-80px] right-[-80px] w-80 h-80 rounded-full
                bg-purple-400/10 blur-3xl pointer-events-none"
         style="animation: float 4s ease-in-out infinite reverse"></div>
    <div class="absolute top-1/2 left-1/4 w-64 h-64 rounded-full
                bg-indigo-300/5 blur-2xl pointer-events-none"></div>

    <div class="absolute inset-x-0 top-8 mx-auto hidden max-w-6xl px-6 lg:flex items-center justify-between text-white/80">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/12 backdrop-blur-md ring-1 ring-white/20">
                <span class="text-xl font-black">M</span>
            </div>
            <div>
                <p class="text-lg font-bold tracking-tight">MOSIP</p>
                <p class="text-sm text-white/60">Monitoring Operational Sipil &amp; Perumahan</p>
            </div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-3 backdrop-blur-md">
            <p class="text-sm font-semibold">Government Dashboard UI</p>
            <p class="text-xs text-white/60">Blade + Tailwind + Alpine.js</p>
        </div>
    </div>

    {{-- Card --}}
    <div class="relative w-full max-w-md mx-4 animate-fade-in">

        {{-- Glass card --}}
        <div class="rounded-[2rem] border border-white/60 bg-white/92 p-8 shadow-2xl backdrop-blur-xl">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-600 to-purple-600 shadow-2xl animate-float">
                    <span class="text-white font-black text-2xl leading-none">M</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800">Selamat Datang</h1>
                <p class="mt-1 text-sm text-slate-500">Masuk ke <span class="text-gradient font-bold">MOSIP</span> Dashboard</p>
                <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50/80 px-3 py-1.5 text-[11px] font-medium text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse-soft"></span>
                    Sistem aktif dan siap sinkronisasi
                </div>
            </div>

            {{-- Session errors --}}
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}"
                  x-data="{ loading: false, showPass: false }"
                  @submit="loading = true">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="relative">
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="email"
                               placeholder="nama@instansi.go.id"
                               class="form-input !pl-10">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="relative">
                        <input id="password"
                               :type="showPass ? 'text' : 'password'"
                               name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="form-input !pl-10 !pr-10">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-400">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                        Lupa password?
                    </a>
                </div>

                <button type="submit"
                        class="btn-primary w-full justify-center py-3 text-base"
                        :disabled="loading">
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-show="!loading">Masuk ke Dashboard</span>
                    <span x-show="loading" x-cloak>Memproses...</span>
                </button>

                {{-- SSO Integration (Portal 1Tep) --}}
                <div class="mt-8">
                    <div class="relative flex items-center justify-center mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <span class="relative bg-white px-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Masuk melalui Portal 1Tep</span>
                    </div>

                    <a href="{{ config('sso.portal_login_url') }}" 
                       class="flex items-center justify-center gap-4 p-5 rounded-[2rem] bg-indigo-600 text-white hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all duration-500 group">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-white font-black text-xl">1</span>
                        </div>
                        <div class="flex flex-col items-start leading-tight">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-80">Login via</span>
                            <span class="text-lg font-black tracking-tight">Portal 1Tep</span>
                        </div>
                    </a>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <a href="{{ route('login.sso', 'sinergi') }}" 
                           class="flex items-center justify-center gap-2 p-3 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-lg transition-all text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600">
                            Sinergi
                        </a>
                        <a href="{{ route('login.sso', 'disimuti') }}" 
                           class="flex items-center justify-center gap-2 p-3 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-lg transition-all text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600">
                            Disimuti
                        </a>
                    </div>
                </div>
            </form>

            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-slate-400">
                © {{ date('Y') }} MOSIP · Monitoring Operational Sipil &amp; Perumahan
            </p>
        </div>

        {{-- Version badge --}}
        <div class="text-center mt-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white/80 text-xs backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                v1.0.0 · Sistem Aktif
            </span>
        </div>
    </div>
</div>

</body>
</html>
