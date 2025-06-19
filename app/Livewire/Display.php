<?php
// zayidx/jam_masjid_digital/Jam_masjid_digital-952dd6084a61afc378bb36d0532d50545135b829/app/Livewire/Display.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\RunningText;
use App\Models\MediaGallery;
use App\Models\IslamicContent;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

#[Layout('livewire.layout.display-layout')]
class Display extends Component
{
    public $settings = [];
    public $currentDate = [];
    public $prayerTimes = [];
    public $nextPrayer = '';
    public $runningTexts = [];
    public $contentQueue = [];
    public $currentItem = null;
    public $currentItemIndex = 0;
    public $displayMode = 'normal';
    public $countdownTimer = '00:00';
    public $countdownTimerFormatted = '00:00';
    public $isAsleep = false;

    // --- PENAMBAHAN: Properti untuk menyimpan timestamp sholat berikutnya ---
    public $nextPrayerTimestamp = 0;

    public function mount()
    {
        $this->loadAllData();
        $this->buildContentQueue();
        if (!empty($this->contentQueue)) {
            $this->cycleContent();
        }
    }

    #[On('forceRebootDisplay')]
    public function rebootPage()
    {
        $this->dispatch('force-reload');
    }

    public function loadAllData()
    {
        $this->loadSettings();
        $this->loadPrayerTimes();
        $this->loadDates();
        $this->loadRunningTexts();
    }

    public function loadSettings()
    {
        $this->settings = Setting::pluck('value', 'key')->toArray();
    }

    public function loadPrayerTimes()
    {
        $cityId = $this->settings['location_city_id'] ?? '1307';
        $cacheKey = 'prayer_times_' . $cityId . '_' . date('Y-m-d');

        $cachedTimes = Cache::get($cacheKey);
        if ($cachedTimes) {
            $this->prayerTimes = $cachedTimes;
            return;
        }

        if ($this->fetchFromMyQuran($cacheKey)) {
            return; 
        }

        Log::warning('Primary API (MyQuran) failed. Attempting fallback API (Al-Adhan).');
        if ($this->fetchFromAladhan($cacheKey)) {
            return; 
        }
        
        $this->prayerTimes = [];
    }

    private function fetchFromMyQuran($cacheKey): bool
    {
        try {
            $cityId = $this->settings['location_city_id'] ?? '1307';
            $response = Http::timeout(10)->get("https://api.myquran.com/v1/sholat/jadwal/{$cityId}/" . date('Y/m/d'));

            if ($response->successful() && str_starts_with($response->header('Content-Type'), 'application/json') && isset($response->json()['status']) && $response->json()['status']) {
                $data = $response->json()['data']['jadwal'];
                
                // KOMENTAR: Baris berikut diubah. 'terbit' diganti menjadi 'syuruq' dan 'dhuha' dihapus.
                $this->prayerTimes = [
                    'imsak'  => $data['imsak'] ?? '-', 'subuh'  => $data['subuh'] ?? '-',
                    'syuruq' => $data['terbit'] ?? '-', // Menggunakan data 'terbit' untuk 'syuruq'
                    'dzuhur' => $data['dzuhur'] ?? '-', 'ashar'  => $data['ashar'] ?? '-',
                    'maghrib'=> $data['maghrib'] ?? '-', 'isya'   => $data['isya'] ?? '-',
                ];
                Cache::put($cacheKey, $this->prayerTimes, now()->endOfDay());
                Log::info('Successfully fetched prayer times from MyQuran API.');
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching from MyQuran API: ' . $e->getMessage());
        }
        return false;
    }

    private function fetchFromAladhan($cacheKey): bool
    {
        try {
            $city = $this->settings['mosque_city_name'] ?? 'Bekasi';
            $country = $this->settings['country_name'] ?? 'Indonesia';
            $response = Http::timeout(15)->get("http://api.aladhan.com/v1/timingsByCity", [
                'city' => $city, 'country' => $country, 'method' => 4 // Kemenag RI
            ]);
    
            if ($response->successful() && $response->json()['code'] == 200) {
                $data = $response->json()['data']['timings'];
    
                // KOMENTAR: Blok logika untuk menghitung waktu Dhuha dihapus karena tidak lagi diperlukan.
    
                // KOMENTAR: Baris berikut diubah. 'terbit' diganti menjadi 'syuruq' menggunakan data 'Sunrise' dan 'dhuha' dihapus.
                $this->prayerTimes = [
                    'imsak'  => $data['Imsak'] ?? '-', 'subuh'  => $data['Fajr'] ?? '-',
                    'syuruq' => $data['Sunrise'] ?? '-', // Menggunakan data 'Sunrise' untuk 'syuruq'
                    'dzuhur' => $data['Dhuhr'] ?? '-', 'ashar'  => $data['Asr'] ?? '-',
                    'maghrib'=> $data['Maghrib'] ?? '-', 'isya'   => $data['Isha'] ?? '-',
                ];
    
                Cache::put($cacheKey, $this->prayerTimes, now()->endOfDay());
                Log::info('Successfully fetched prayer times from Al-Adhan API.');
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Exception when fetching from Al-Adhan API: ' . $e->getMessage());
        }
        return false;
    }

    public function loadDates()
    {
        $now = Carbon::now();
        $this->currentDate['masehi'] = $now->translatedFormat('l, d F Y');

        try {
            $response = Http::get("https://api.aladhan.com/v1/gToH?date=" . $now->format('d-m-Y'));
            if ($response->successful()) {
                $hijriDate = $response->json()['data']['hijri'];
                $this->currentDate['hijriah'] = $hijriDate['day'] . ' ' . $hijriDate['month']['en'] . ' ' . $hijriDate['year'] . ' H';
            } else {
                $this->currentDate['hijriah'] = '';
            }
        } catch (\Exception $e) {
            $this->currentDate['hijriah'] = '';
            Log::error('Failed to fetch Hijri date: ' . $e->getMessage());
        }
    }

    public function loadRunningTexts()
    {
        $this->runningTexts = RunningText::where('is_active', true)->get();
    }

    public function buildContentQueue()
    {
        $media = MediaGallery::where('is_active', true)->get();
        $islamic = IslamicContent::where('is_active', true)->get();
        
        $this->contentQueue = $media->concat($islamic)->shuffle()->all();

        if ((!isset($this->settings['qris_display_mode']) || $this->settings['qris_display_mode'] == 'cycle') && isset($this->settings['qris_image_path'])) {
             array_push($this->contentQueue, (object)[
                'type' => 'qris', 
                'file_path' => $this->settings['qris_image_path']
            ]);
        }
    }

    public function cycleContent()
    {
        if (!empty($this->contentQueue)) {
            $this->currentItem = $this->contentQueue[$this->currentItemIndex];
            $this->currentItemIndex = ($this->currentItemIndex + 1) % count($this->contentQueue);
        }
    }
    
    // --- PERUBAHAN: Modifikasi method untuk menghitung sisa waktu ke sholat berikutnya ---
    public function findNextPrayer()
    {
        $now = now();
        $this->nextPrayer = '';
        $this->nextPrayerTimestamp = 0; // Reset timestamp setiap pengecekan
        $prayerOrder = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

        if (!empty($this->prayerTimes)) {
            foreach ($prayerOrder as $prayer) {
                if (isset($this->prayerTimes[$prayer]) && $this->prayerTimes[$prayer] !== '-') {
                    $prayerTime = Carbon::createFromTimeString($this->prayerTimes[$prayer]);
                    if ($now->lessThan($prayerTime)) {
                        $this->nextPrayer = $prayer;
                        // Menyimpan waktu target dalam format UNIX timestamp
                        $this->nextPrayerTimestamp = $prayerTime->timestamp;
                        break;
                    }
                }
            }
        }

        // Jika tidak ditemukan, berarti sholat berikutnya adalah Subuh esok hari
        if (empty($this->nextPrayer)) {
            $this->nextPrayer = 'subuh';
            if (isset($this->prayerTimes['subuh']) && $this->prayerTimes['subuh'] !== '-') {
                // Buat waktu Subuh untuk besok
                $tomorrowSubuh = Carbon::createFromTimeString($this->prayerTimes['subuh'])->addDay();
                $this->nextPrayerTimestamp = $tomorrowSubuh->timestamp;
            }
        }
    }

    public function startAdhan($prayerName)
    {
        $this->displayMode = 'adhan';
        $this->nextPrayer = $prayerName;

        $soundPath = $this->settings['adhan_sound_path'] ?? '/sounds/adhan.mp3';
        $this->dispatch('play-sound', sound: $soundPath);

        $iqamahDuration = (int)($this->settings['iqamah_duration_' . $prayerName] ?? 10);
        $this->countdownTimer = $iqamahDuration * 60;
        
        $adhanDurationMs = ((int)($this->settings['adhan_duration_seconds'] ?? 180)) * 1000;
        $this->dispatch('start-countdown-after', delay: $adhanDurationMs);
    }
    
    #[On('startCountdown')]
    public function startCountdown()
    {
        if ($this->displayMode == 'adhan') {
            $this->displayMode = 'countdown';
        }
    }

    public function updateCountdown()
    {
        if ($this->countdownTimer <= 0) {
            $this->displayMode = 'normal';
            $this->countdownTimerFormatted = '00:00';
            $reminderSound = $this->settings['iqamah_reminder_sound'] ?? '/sounds/iqamah_reminder.mp3';
            $this->dispatch('play-sound', sound: $reminderSound);
        } else {
            $this->countdownTimer--;
            $minutes = floor($this->countdownTimer / 60);
            $seconds = $this->countdownTimer % 60;
            $this->countdownTimerFormatted = sprintf('%02d:%02d', $minutes, $seconds);
        }
    }

    public function checkForSimulation()
    {
        $this->loadSettings();
        $simulation = $this->settings['simulation_request'] ?? null;
        
        if ($simulation && str_contains($simulation, '|')) {
            list($prayerName, $timestamp) = explode('|', $simulation, 2);
            if (now()->timestamp - $timestamp < 15) { 
                Setting::where('key', 'simulation_request')->update(['value' => null]);
                $this->settings['simulation_request'] = null;
                $this->startAdhan($prayerName);
                return true; 
            }
        }
        return false; 
    }

    public function tick()
    {
        $now = now();
        $sleepTime = $this->settings['sleep_time'] ?? '23:00';
        $wakeTime = $this->settings['wake_time'] ?? '03:00';
        
        if ($now->copy()->format('H:i') >= $sleepTime || $now->copy()->format('H:i') < $wakeTime) {
            $this->isAsleep = true;
            return;
        }
        $this->isAsleep = false;
        
        if (empty($this->prayerTimes)) {
            $this->loadPrayerTimes();
        }

        if ($this->checkForSimulation()) {
            return;
        }
        
        $this->findNextPrayer();

        if ($this->displayMode == 'normal') {
            if (!empty($this->prayerTimes)) {
                foreach($this->prayerTimes as $prayerName => $prayerTime) {
                    if ($prayerTime !== '-' && $prayerTime !== 'N/A' && Carbon::createFromTimeString($prayerTime)->format('H:i') === $now->format('H:i')) {
                         $this->startAdhan($prayerName);
                         return;
                    }
                }
            }
            if ($now->second % 15 == 0) {
                $this->cycleContent();
            }
        }
        
        if ($this->displayMode == 'countdown') {
            $this->updateCountdown();
        }
    }

    public function render()
    {
        return view('livewire.display');
    }
}