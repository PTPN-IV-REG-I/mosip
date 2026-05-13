@props(['title' => '', 'class' => ''])

<div class="glass-card {{ $class }}">
    @if ($title)
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-800">{{ $title }}</h3>
                <div class="mt-2 h-1.5 w-12 rounded-full bg-gradient-to-r from-primary-500 to-purple-500/70"></div>
            </div>
            {{ $actions ?? '' }}
        </div>
    @endif
    {{ $slot }}
</div>
