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

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                heading: ['Poppins', 'sans-serif'],
            },
            colors: {
                brand: {
                    DEFAULT: '#2563EB',
                    light: '#EFF6FF',
                    dark: '#1E3A5F',
                },
                surface: {
                    DEFAULT: '#F8FAFC',
                    card: '#FFFFFF',
                },
                text: {
                    primary: '#1E293B',
                    secondary: '#334155',
                    tertiary: '#64748B',
                    muted: '#94A3B8',
                },
                border: {
                    DEFAULT: '#E2E8F0',
                    light: '#CBD5E1',
                },
                status: {
                    blue: '#3B82F6',
                    amber: '#D97706',
                    green: '#16A34A',
                    red: '#DC2626',
                },
                grade: {
                    a: '#065F46',
                    b: '#1E40AF',
                    c: '#92400E',
                    d: '#991B1B',
                },
                accent: {
                    DEFAULT: '#EA580C',
                    light: '#FFF7ED',
                    dark: '#9A3412',
                },
            },
        },
    },

    plugins: [forms],
};
