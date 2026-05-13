@props(['value' => 'info', 'class' => ''])

@php
$styles = [
    'aktif'    => 'badge badge-green',
    'nonaktif' => 'badge badge-red',
    'pending'  => 'badge badge-yellow',
    'info'     => 'badge badge-blue',
    'warning'  => 'badge badge-yellow',
    'danger'   => 'badge badge-red',
    'success'  => 'badge badge-green',
];
$dots = [
    'aktif'    => 'bg-emerald-500',
    'nonaktif' => 'bg-red-500',
    'pending'  => 'bg-amber-500',
    'info'     => 'bg-primary-500',
    'warning'  => 'bg-amber-500',
    'danger'   => 'bg-red-500',
    'success'  => 'bg-emerald-500',
];
$cls = $styles[strtolower($value)] ?? 'badge badge-blue';
$dot = $dots[strtolower($value)] ?? 'bg-primary-500';
@endphp

<span class="{{ $cls }} {{ $class }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ ucfirst($value) }}
</span>
