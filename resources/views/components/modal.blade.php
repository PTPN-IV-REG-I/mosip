@props(['show' => false, 'title' => 'Modal', 'size' => 'md'])

@php
$sizeClass = match($size) {
    'sm'  => 'max-w-md',
    'lg'  => 'max-w-3xl',
    'xl'  => 'max-w-5xl',
    default => 'max-w-lg',
};
@endphp

<div x-show="{{ $show }}" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm"
         @click="{{ $close ?? 'open = false' }}"></div>

    {{-- Panel --}}
    <div class="relative z-10 w-full {{ $sizeClass }} overflow-hidden rounded-[1.75rem] border border-white/70 bg-white/90 shadow-2xl"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100/90 px-6 py-5">
            <div>
                <h3 class="text-base font-semibold text-slate-800">{{ $title }}</h3>
                <p class="mt-1 text-xs text-slate-400">Lengkapi data lalu simpan perubahan.</p>
            </div>
            <button @click="{{ $close ?? 'open = false' }}"
                    class="btn-icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 bg-white/80">{{ $slot }}</div>

        {{-- Footer --}}
        @isset($footer)
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 rounded-b-[1.75rem]">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
