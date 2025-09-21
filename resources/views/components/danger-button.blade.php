@php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 '.($isDemo ? 'bg-red-300 cursor-not-allowed opacity-60' : 'bg-red-600').' border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest '.($isDemo ? '' : 'hover:bg-red-500 active:bg-red-700').' focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }} @if($isDemo) disabled @endif>
    {{ $slot }}
</button>
