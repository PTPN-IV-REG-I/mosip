@props(['status' => 'online', 'label' => 'Sinkronisasi berhasil', 'time' => null])

@php
$dot = match($status) {
    'warning' => 'bg-amber-400',
    'error'   => 'bg-red-500',
    default   => 'bg-emerald-500',
};
$ring = match($status) {
    'warning' => 'bg-amber-400/30',
    'error'   => 'bg-red-500/30',
    default   => 'bg-emerald-500/30',
};
@endphp

<div class="surface-panel flex items-center gap-2.5 rounded-2xl px-4 py-2.5">
    <div class="relative flex items-center justify-center w-3 h-3">
        <span class="absolute inline-flex w-full h-full rounded-full {{ $ring }} animate-pulse-soft opacity-75"></span>
        <span class="relative inline-flex w-2.5 h-2.5 rounded-full {{ $dot }}"></span>
    </div>
    <div>
        <p class="text-xs font-semibold text-slate-700">{{ $label }}</p>
        @if ($time)
            <p class="text-[10px] text-slate-400">{{ $time }}</p>
        @endif
    </div>
</div>
