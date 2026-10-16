@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
$baseClasses = 'rounded-lg border px-4 py-3 my-2';

$typeClasses = [
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'danger' => 'bg-red-50 border-red-200 text-red-800',
];

$icon = [
    'info' => 'ℹ️',
    'success' => '✅',
    'warning' => '⚠️',
    'danger' => '❌',
];

$classes = $baseClasses . ' ' . ($typeClasses[$type] ?? $typeClasses['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-start">
        <div class="mr-3 mt-0.5">{{ $icon[$type] ?? 'ℹ️' }}</div>
        <div class="flex-1">
            {{ $slot }}
        </div>
        @if($dismissible)
            <button type="button" class="ml-3 text-current hover:opacity-70 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                ✖️
            </button>
        @endif
    </div>
</div>
