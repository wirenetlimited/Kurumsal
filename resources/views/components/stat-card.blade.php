@props(['title' => '', 'value' => '', 'subtitle' => '', 'color' => 'bg-primary-600'])
<div {{ $attributes->merge(['class' => "$color text-white rounded p-4 shadow"]) }}>
  <div class="text-sm opacity-80">{{ $title }}</div>
  <div class="text-3xl font-semibold">{{ $value }}</div>
  @if($subtitle)
    <div class="text-xs opacity-80 mt-1">{{ $subtitle }}</div>
  @endif
</div>

