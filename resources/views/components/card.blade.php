@props([
    'title' => null,
    'padding' => true,
])

@php
$classes = 'bg-white rounded-lg shadow-sm border border-slate-200';
$paddingClass = $padding ? 'p-6' : '';
@endphp

<div {{ $attributes->merge(['class' => "$classes $paddingClass"]) }}>
    @if($title)
        <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ $title }}</h3>
    @endif
    
    {{ $slot }}
</div>
