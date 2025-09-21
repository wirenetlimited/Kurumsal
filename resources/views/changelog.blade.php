@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        📋 Sürüm Notları
                    </h1>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Dashboard'a Dön
                    </a>
                </div>
                
                <div class="prose prose-lg dark:prose-invert max-w-none">
                    {!! Str::markdown($changelogContent) !!}
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Sürüm Takibi Hakkında
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>
                                        Bu sayfada projemizin tüm sürüm güncellemelerini ve geliştirme notlarını bulabilirsiniz. 
                                        Her sürüm numarası, yapılan değişiklikleri ve iyileştirmeleri detaylı olarak açıklar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.prose h1 {
    @apply text-3xl font-bold text-gray-900 dark:text-white mb-6;
}

.prose h2 {
    @apply text-2xl font-semibold text-gray-800 dark:text-gray-200 mt-8 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2;
}

.prose h3 {
    @apply text-xl font-medium text-gray-700 dark:text-gray-300 mt-6 mb-3;
}

.prose h4 {
    @apply text-lg font-medium text-gray-600 dark:text-gray-400 mt-4 mb-2;
}

.prose ul {
    @apply list-disc list-inside space-y-1 text-gray-600 dark:text-gray-400;
}

.prose li {
    @apply text-gray-600 dark:text-gray-400;
}

.prose strong {
    @apply font-semibold text-gray-800 dark:text-gray-200;
}

.prose code {
    @apply bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded text-sm font-mono;
}

.prose pre {
    @apply bg-gray-100 dark:bg-gray-700 p-4 rounded-lg overflow-x-auto;
}

.prose blockquote {
    @apply border-l-4 border-blue-500 pl-4 italic text-gray-600 dark:text-gray-400;
}

.prose hr {
    @apply border-gray-200 dark:border-gray-700 my-8;
}

.prose a {
    @apply text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline;
}

.prose table {
    @apply w-full border-collapse border border-gray-200 dark:border-gray-700;
}

.prose th {
    @apply border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-50 dark:bg-gray-700 text-left text-sm font-medium text-gray-900 dark:text-white;
}

.prose td {
    @apply border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-gray-600 dark:text-gray-400;
}
</style>
@endsection
