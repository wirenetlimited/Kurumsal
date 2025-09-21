@php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 '.($isDemo ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-gray-800 dark:bg-gray-200').' border border-transparent rounded-md font-semibold text-xs '.($isDemo ? 'text-white' : 'text-white dark:text-gray-800').' uppercase tracking-widest '.($isDemo ? '' : 'hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300').' focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }} @if($isDemo) disabled @endif>
    {{ $slot }}
</button>
