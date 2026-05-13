@props([
    'value'  => '0',
    'label'  => '',
    'change' => null,
    'color'  => 'from-primary-500 to-indigo-600',
    'suffix' => '',
])

<div class="stat-card">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0 pr-3">
            <p class="mb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $label }}</p>
            <p class="mt-1 text-3xl font-extrabold text-slate-800 leading-none">
                {{ $value }}<span class="text-base font-medium text-slate-400 ml-1">{{ $suffix }}</span>
            </p>
            @if ($change !== null)
                <div class="flex items-center gap-1 mt-2">
                    @if (str_starts_with($change, '+'))
                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        <span class="text-xs font-semibold text-emerald-600">{{ $change }}</span>
                    @else
                        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        <span class="text-xs font-semibold text-red-500">{{ $change }}</span>
                    @endif
                    <span class="text-xs text-slate-400">dari bulan lalu</span>
                </div>
            @endif
        </div>

        @if (isset($icon))
            <div class="stat-card-icon bg-gradient-to-br {{ $color }} shadow-md animate-float shrink-0">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
