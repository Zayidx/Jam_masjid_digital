// Mengimpor fungsi `defineConfig` dari pustaka Vite
import { defineConfig } from 'vite';
// Mengimpor plugin Laravel untuk Vite
import laravel from 'laravel-vite-plugin';

// Mengekspor konfigurasi default
export default defineConfig({
    // Daftar plugin yang digunakan oleh Vite
    plugins: [
        laravel({
            // File CSS dan JS utama yang akan diproses
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // Mengaktifkan hot-reload untuk file Blade
            refresh: true,
        }),
    ],
    // =======================================================
    // ---- TAMBAHKAN BLOK KONFIGURASI SERVER DI BAWAH INI ----
    // =======================================================
    server: {
        // Perintahkan Vite untuk mendengarkan koneksi dari semua alamat IP di jaringan,
        // tidak hanya 'localhost'. Ini adalah kunci agar HP bisa mengakses server Vite.
        host: true,
    }
});