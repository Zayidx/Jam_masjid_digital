// Mengimpor tema default dari Tailwind CSS.
import defaultTheme from 'tailwindcss/defaultTheme';
// Mengimpor plugin form dari Tailwind.
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
// Mengekspor konfigurasi default.
export default {
    // Menentukan file-file yang akan dipindai oleh Tailwind untuk mencari kelas utilitas.
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Mendefinisikan tema kustom.
    theme: {
        // Memperluas tema default.
        extend: {
            // Mendefinisikan keluarga font kustom.
            fontFamily: {
                // Menambahkan 'Figtree' sebagai font sans-serif default.
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // PENYESUAIAN: Mendaftarkan 'font-arabic' di sini.
                arabic: ['"Noto Naskh Arabic"', 'serif'],
            },
            
            // HAPUS BAGIAN KEYFRAMES DAN ANIMATION DARI SINI
        },
    },

    // Mendaftarkan plugin yang digunakan.
    plugins: [forms],
};