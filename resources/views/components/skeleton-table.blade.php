@props(['rows' => 5, 'cols' => 4])

<div class="table-shell">
    {{-- Header skeleton --}}
    <div class="flex gap-4 px-4 py-3 bg-slate-50 border-b border-slate-100">
        @for ($c = 0; $c < $cols; $c++)
            <div class="skeleton h-3 flex-1 rounded"></div>
        @endfor
    </div>
    {{-- Row skeletons --}}
    @for ($r = 0; $r < $rows; $r++)
        <div class="flex gap-4 px-4 py-3.5 border-b border-slate-50"
             style="animation-delay: {{ $r * 80 }}ms">
            @for ($c = 0; $c < $cols; $c++)
                <div class="skeleton h-3 rounded"
                     style="flex: {{ $c === 0 ? '0 0 32px' : '1' }}"></div>
            @endfor
        </div>
    @endfor
</div>
