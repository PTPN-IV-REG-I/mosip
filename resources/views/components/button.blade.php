@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'href' => null])

@php
$baseSize = match($size) {
    'sm' => ' px-3 py-2 text-xs rounded-xl',
    'lg' => ' px-5 py-3 text-sm rounded-2xl',
    default => '',
};

$classes = match($variant) {
    'secondary' => 'btn-secondary',
    'danger'    => 'btn-danger',
    'ghost'     => 'btn-icon',
    default     => 'btn-primary',
} . $baseSize;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
