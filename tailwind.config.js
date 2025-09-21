import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'bg-primary-600',
        'bg-success-600',
        'bg-warning-500',
        'bg-danger-600',
        'bg-plum-600',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    600: '#2563eb',
                    700: '#1d4ed8',
                },
                success: { 600: '#16a34a' },
                warning: { 500: '#f59e0b' },
                danger: { 600: '#ef4444' },
                plum:   { 600: '#7c3aed' },
                accent: {
                    'peter-river': '#3498db',
                    'emerald': '#2ecc71',
                    'amethyst': '#9b59b6',
                    'pumpkin': '#e67e22',
                },
            },
            container: { center: true, padding: '1rem' },
        },
    },

    plugins: [forms],
};
