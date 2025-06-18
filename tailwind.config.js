import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // PENYESUAIAN: Daftarkan 'font-arabic' di sini
                arabic: ['"Noto Naskh Arabic"', 'serif'],
            },
            // PENYESUAIAN: Daftarkan animasi marquee di sini
            keyframes: {
                marquee: {
                  '0%': { transform: 'translateX(100%)' },
                  '100%': { transform: 'translateX(-120%)' },
                }
            },
            animation: {
                marquee: 'marquee 40s linear infinite',
            }
        },
    },

    plugins: [forms],
};