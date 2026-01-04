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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'slide-in-from-right': {
                    '0%': { opacity: '0', transform: 'translateX(50%)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'slide-in-from-left': {
                    '0%': { opacity: '0', transform: 'translateX(-50%)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'glow-primary': {
                    '50%': { boxShadow: '0 0 20px rgba(0, 223, 216, 0.6)' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.8s ease-out forwards',
                'slide-in-right': 'slide-in-from-right 0.5s ease-out forwards',
                'slide-in-left': 'slide-in-from-left 0.5s ease-out forwards',
                'glow-primary': 'glow-primary 2.5s ease-in-out infinite',
            }
        },
    },

    plugins: [forms],
};