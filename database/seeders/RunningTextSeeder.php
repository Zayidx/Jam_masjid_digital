<?php
// Mendefinisikan namespace untuk seeder, sesuai dengan struktur direktori Laravel.
namespace Database\Seeders;

// Mengimpor kelas Seeder dasar dari Laravel.
use Illuminate\Database\Seeder;
// Mengimpor model RunningText agar dapat digunakan untuk berinteraksi dengan database.
use App\Models\RunningText;
// Mengimpor facade DB untuk operasi database tingkat lanjut jika diperlukan (di sini tidak digunakan, tapi praktik yang baik untuk diimpor jika ada).
use Illuminate\Support\Facades\DB;

// Mendefinisikan kelas seeder yang akan mengisi data teks berjalan.
class RunningTextSeeder extends Seeder
{
    /**
     * Menjalankan proses seeding (pengisian data) ke database.
     *
     * @return void
     */
    public function run(): void
    {
        // Menghapus semua data yang ada di tabel 'running_texts' untuk memulai dari awal.
        // Ini mencegah duplikasi data jika seeder dijalankan lebih dari sekali.
        DB::table('running_texts')->delete();

        // Mendefinisikan sebuah array yang berisi data teks berjalan yang akan dimasukkan.
        $texts = [
            // Setiap elemen array adalah string yang berisi konten teks berjalan.
            'Selamat datang di Masjid Jami\' Baiturrahman. Semoga Allah SWT menerima amal ibadah kita semua.',
            'Mohon untuk mematikan atau mengheningkan suara telepon seluler Anda saat berada di dalam masjid.',
            'Jagalah kebersihan dan kesucian masjid. Kebersihan adalah sebagian dari iman.',
            'Mari kita luruskan dan rapatkan shaf untuk kesempurnaan shalat berjamaah.',
            'Dilarang merokok di seluruh area masjid. Terima kasih atas pengertiannya.',
            'Manfaatkan waktu antara adzan dan iqamah untuk berdoa dan berdzikir, karena itu adalah waktu yang mustajab.',
        ];

        // Melakukan perulangan (looping) untuk setiap teks dalam array $texts.
        foreach ($texts as $textContent) {
            // Membuat record baru di tabel 'running_texts' untuk setiap teks.
            RunningText::create([
                // Mengisi kolom 'content' dengan nilai dari variabel $textContent.
                'content' => $textContent,
                // Mengisi kolom 'is_active' dengan nilai true, yang berarti teks ini akan aktif dan ditampilkan.
                'is_active' => true,
            ]);
        }
    }
}