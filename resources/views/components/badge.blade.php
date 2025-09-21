@props(['variant' => 'default'])
@php
  $classes = [
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'danger' => 'badge-danger',
    'default' => 'badge-default',
  ][$variant] ?? 'badge-default';
@endphp
<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>

