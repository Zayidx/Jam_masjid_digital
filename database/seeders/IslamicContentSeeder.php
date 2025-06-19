<?php
// Mendefinisikan namespace untuk seeder, sesuai dengan struktur direktori Laravel.
namespace Database\Seeders;

// Mengimpor kelas Seeder dasar dari Laravel.
use Illuminate\Database\Seeder;
// Mengimpor model IslamicContent agar dapat digunakan untuk berinteraksi dengan database.
use App\Models\IslamicContent;
// Mengimpor facade DB untuk operasi database tingkat lanjut, seperti menghapus data tabel.
use Illuminate\Support\Facades\DB;

// Mendefinisikan kelas seeder yang akan mengisi data konten Islami.
class IslamicContentSeeder extends Seeder
{
    /**
     * Menjalankan proses seeding (pengisian data) ke database.
     *
     * @return void
     */
    public function run(): void
    {
        // Menghapus semua data yang ada di tabel 'islamic_contents' untuk memastikan data selalu baru setiap kali seeder dijalankan.
        DB::table('islamic_contents')->delete();

        // Mendefinisikan sebuah array yang berisi data konten Islami (Al-Quran dan Hadits).
        $contents = [
            // Konten pertama (Ayat Al-Quran)
            [
                'type' => 'quran', // Menandakan bahwa ini adalah konten dari Al-Quran.
                'text_ar' => 'يَا أَيُّهَا الَّذِينَ آمَنُوا كُتِبَ عَلَيْكُمُ الصِّيَامُ كَمَا كُتِبَ عَلَى الَّذِينَ مِن قَبْلِكُمْ لَعَلَّكُمْ تَتَّقُونَ', // Teks ayat dalam bahasa Arab.
                'text_id' => 'Wahai orang-orang yang beriman! Diwajibkan atas kamu berpuasa sebagaimana diwajibkan atas orang sebelum kamu agar kamu bertakwa.', // Terjemahan ayat dalam bahasa Indonesia.
                'source' => 'Q.S. Al-Baqarah: 183', // Sumber ayat.
                'is_active' => true, // Menandakan konten ini aktif.
            ],
            // Konten kedua (Ayat Al-Quran)
            [
                'type' => 'quran', // Tipe konten adalah Al-Quran.
                'text_ar' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا', // Teks ayat dalam bahasa Arab.
                'text_id' => 'Maka sesungguhnya beserta kesulitan ada kemudahan.', // Terjemahan ayat.
                'source' => 'Q.S. Al-Insyirah: 5', // Sumber ayat.
                'is_active' => true, // Status aktif.
            ],
            // Konten ketiga (Hadits)
            [
                'type' => 'hadith', // Menandakan bahwa ini adalah konten Hadits.
                'text_ar' => null, // Teks Arab dikosongkan (opsional).
                'text_id' => 'Barangsiapa yang menunjuki kepada kebaikan maka dia akan mendapatkan pahala seperti pahala orang yang mengerjakannya.', // Isi Hadits dalam bahasa Indonesia.
                'source' => 'H.R. Muslim', // Sumber Hadits.
                'is_active' => true, // Status aktif.
            ],
            // Konten keempat (Hadits)
            [
                'type' => 'hadith', // Tipe konten adalah Hadits.
                'text_ar' => 'الطُّهُورُ شَطْرُ الْإِيمَانِ', // Teks Hadits dalam bahasa Arab.
                'text_id' => 'Kesucian itu adalah setengah dari iman.', // Terjemahan Hadits.
                'source' => 'H.R. Muslim', // Sumber Hadits.
                'is_active' => true, // Status aktif.
            ],
             // Konten kelima (Hadits)
            [
                'type' => 'hadith', // Tipe konten adalah Hadits.
                'text_ar' => 'إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ', // Teks Hadits dalam bahasa Arab.
                'text_id' => 'Sesungguhnya setiap amalan tergantung pada niatnya.', // Terjemahan Hadits.
                'source' => 'H.R. Bukhari & Muslim', // Sumber Hadits.
                'is_active' => true, // Status aktif.
            ],
        ];

        // Melakukan perulangan (looping) untuk setiap konten dalam array $contents.
        foreach ($contents as $contentData) {
            // Membuat record baru di tabel 'islamic_contents' menggunakan data dari array.
            IslamicContent::create($contentData);
        }
    }
}