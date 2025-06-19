<?php
// zayidx/jam_masjid_digital/Jam_masjid_digital-952dd6084a61afc378bb36d0532d50545135b829/database/seeders/SettingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'mosque_name', 'value' => 'Masjid Jami\' Baiturrahman'],
            ['key' => 'mosque_address', 'value' => 'Margahayu, Bekasi Timur'],
            ['key' => 'mosque_city_name', 'value' => 'Kota Bekasi'],
            // --- PENAMBAHAN: Menambahkan pengaturan untuk negara, diperlukan oleh API cadangan ---
            ['key' => 'country_name', 'value' => 'Indonesia'],
            ['key' => 'location_city_id', 'value' => '1307'],
            ['key' => 'theme_bg_color', 'value' => '#064e3b'],
            ['key' => 'theme_text_color', 'value' => '#ffffff'],
            ['key' => 'master_volume', 'value' => '80'],
            ['key' => 'qris_display_mode', 'value' => 'cycle'],
            ['key' => 'sleep_time', 'value' => '23:00'],
            ['key' => 'wake_time', 'value' => '03:00'],
            ['key' => 'iqamah_duration_subuh', 'value' => '12'],
            ['key' => 'iqamah_duration_dzuhur', 'value' => '8'],
            ['key' => 'iqamah_duration_ashar', 'value' => '8'],
            ['key' => 'iqamah_duration_maghrib', 'value' => '7'],
            ['key' => 'iqamah_duration_isya', 'value' => '8'],
            ['key' => 'running_text_speed', 'value' => '45'],
            ['key' => 'adhan_duration_seconds', 'value' => '180'],
            ['key' => 'simulation_request', 'value' => null],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}