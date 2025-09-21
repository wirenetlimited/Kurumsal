@props(['end' => null])
@php
  $days = is_null($end) ? null : (int)now()->diffInDays(\Carbon\Carbon::parse($end), false);
  $variant = 'success';
  if (!is_null($days)) {
    if ($days < 0 || $days <= 7) { $variant = 'danger'; }
    elseif ($days <= 30) { $variant = 'warning'; }
  }
  $classes = [
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'success' => 'bg-green-100 text-green-800',
  ][$variant];
@endphp
<span class="px-2 py-1 rounded text-xs inline-flex items-center {{ $classes }}">
  @if(!is_null($days) && ($days <= 7))
    <span class="inline-block w-2 h-2 rounded-full bg-red-600 mr-1"></span>
  @endif
  @if(is_null($days))
    -
  @elseif($days < 0)
    {{ abs($days) }} gün geçti
  @elseif($days === 0)
    bugün
  @else
    {{ (int) ceil($days) }} gün
  @endif
</span>

