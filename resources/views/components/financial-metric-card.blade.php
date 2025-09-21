@props([
    'title' => '',
    'value' => '',
    'icon' => 'dollar',
    'color' => 'green',
    'badge' => ''
])

@php
    $colorClasses = [
        'green' => [
            'bg' => 'from-green-500 to-green-600',
            'text' => 'text-green-600 dark:text-green-400',
            'badge' => 'bg-green-100 dark:bg-green-900/30',
            'border' => 'border-green-200 dark:border-green-700',
            'card' => 'from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10'
        ],
        'blue' => [
            'bg' => 'from-blue-500 to-blue-600',
            'text' => 'text-blue-600 dark:text-blue-400',
            'badge' => 'bg-blue-100 dark:bg-blue-900/30',
            'border' => 'border-blue-200 dark:border-blue-700',
            'card' => 'from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10'
        ],
        'orange' => [
            'bg' => 'from-orange-500 to-orange-600',
            'text' => 'text-orange-600 dark:text-orange-400',
            'badge' => 'bg-orange-100 dark:bg-orange-900/30',
            'border' => 'border-orange-200 dark:border-orange-700',
            'card' => 'from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-900/10'
        ],
        'purple' => [
            'bg' => 'from-purple-500 to-purple-600',
            'text' => 'text-purple-600 dark:text-purple-400',
            'badge' => 'bg-purple-100 dark:bg-purple-900/30',
            'border' => 'border-purple-200 dark:border-purple-700',
            'card' => 'from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10'
        ],
        'red' => [
            'bg' => 'from-red-500 to-red-600',
            'text' => 'text-red-600 dark:text-red-400',
            'badge' => 'bg-red-100 dark:bg-red-900/30',
            'border' => 'border-red-200 dark:border-red-700',
            'card' => 'from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-900/10'
        ]
    ];

    $iconPaths = [
        'dollar' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
        'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'trending' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
        'service' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'invoice' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
    ];

    $selectedColor = $colorClasses[$color] ?? $colorClasses['green'];
    $iconPath = $iconPaths[$icon] ?? $iconPaths['dollar'];
@endphp

<div class="bg-gradient-to-br {{ $selectedColor['card'] }} rounded-xl p-6 border {{ $selectedColor['border'] }} hover:shadow-lg transition-all duration-200">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-gradient-to-br {{ $selectedColor['bg'] }} rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
            </svg>
        </div>
        @if($badge)
            <span class="text-sm font-medium {{ $selectedColor['text'] }} {{ $selectedColor['badge'] }} px-3 py-1 rounded-full">{{ $badge }}</span>
        @endif
    </div>
    <div>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $title }}</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
    </div>
</div>
