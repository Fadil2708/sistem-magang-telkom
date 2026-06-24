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
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#C0392B',
                    light: '#F9EAE8',
                    dark: '#1A0A0A',
                },
                surface: {
                    DEFAULT: '#F5F4F2',
                    card: '#FFFFFF',
                },
                text: {
                    primary: '#1E1C1A',
                    secondary: '#312F2D',
                    tertiary: '#5C5A55',
                    muted: '#A8A5A0',
                },
                border: {
                    DEFAULT: '#E8E6E1',
                    light: '#D0CEC9',
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
            },
        },
    },

    plugins: [forms],
};
