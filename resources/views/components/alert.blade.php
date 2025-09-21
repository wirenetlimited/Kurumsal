@props(['variant' => 'warning'])
@php
  $map = [
    'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
  ];
  $cls = $map[$variant] ?? $map['warning'];
@endphp
<div {{ $attributes->merge(['class' => "border px-4 py-3 rounded $cls"]) }}>
  {{ $slot }}
</div>

