@props(['title' => ''])
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">{{ $title }}</h1>
  <div>{{ $actions ?? '' }}</div>
</div>

