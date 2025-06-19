<?php
// Mendefinisikan namespace untuk seeder, sesuai dengan struktur direktori Laravel.
namespace Database\Seeders;

// Kelas ini tidak digunakan secara langsung dalam kode ini, tetapi merupakan bagian dari standar Laravel.
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Mengimpor kelas Seeder dasar dari Laravel.
use Illuminate\Database\Seeder;

// Mendefinisikan kelas DatabaseSeeder utama yang akan memanggil seeder lainnya.
class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan semua seeder aplikasi.
     */
    public function run(): void
    {
        // Memanggil seeder-seeder lain yang telah didefinisikan.
        $this->call([
            // Memanggil SettingSeeder untuk mengisi data pengaturan awal.
            SettingSeeder::class,
            // BARIS BARU: Memanggil RunningTextSeeder untuk mengisi data teks berjalan.
            RunningTextSeeder::class,
            // BARIS BARU: Memanggil IslamicContentSeeder untuk mengisi data konten Islami.
            IslamicContentSeeder::class,
        ]);
    }
}