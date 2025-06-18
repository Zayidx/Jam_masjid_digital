<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'mosque_name', 'value' => 'Masjid Jami\' Al-Hidayah'],
            ['key' => 'mosque_address', 'value' => 'Jl. Pahlawan No. 123, Kota Jakarta'],
            ['key' => 'mosque_city_name', 'value' => 'DKI Jakarta'],
            ['key' => 'location_city_id', 'value' => '1301'],
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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}