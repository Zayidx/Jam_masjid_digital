<?php

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
use Livewire\Attributes\Layout;

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

    protected $listeners = ['forceRebootDisplay' => 'rebootPage', 'startCountdown' => 'startCountdown'];

    public function mount()
    {
        $this->loadAllData();
        $this->buildContentQueue();
        if (!empty($this->contentQueue)) {
            $this->cycleContent();
        }
    }

    public function rebootPage()
    {
        $this->dispatchBrowserEvent('force-reload');
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
        $cityId = $this->settings['location_city_id'] ?? '1301';
        $cacheKey = 'prayer_times_' . $cityId . '_' . date('Y-m-d');
        
        $this->prayerTimes = Cache::remember($cacheKey, now()->endOfDay(), function () use ($cityId) {
            try {
                $response = Http::get("https://api.myquran.com/v1/sholat/jadwal/{$cityId}/" . date('Y/m/d'));
                if ($response->successful() && $response->json()['status']) {
                    $data = $response->json()['data']['jadwal'];
                    return [
                        'imsak'  => $data['imsak'],
                        'subuh'  => $data['subuh'],
                        'terbit' => $data['terbit'],
                        'dhuha'  => $data['dhuha'],
                        'dzuhur' => $data['dzuhur'],
                        'ashar'  => $data['ashar'],
                        'maghrib'=> $data['maghrib'],
                        'isya'   => $data['isya'],
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch prayer times: ' . $e->getMessage());
            }
            return [];
        });
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
    
    public function findNextPrayer()
    {
        $now = now();
        $this->nextPrayer = '';

        $prayerOrder = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

        if(empty($this->prayerTimes)) return;

        foreach ($prayerOrder as $prayer) {
            if (isset($this->prayerTimes[$prayer])) {
                $prayerTime = Carbon::createFromTimeString($this->prayerTimes[$prayer]);
                if ($now->lessThan($prayerTime)) {
                    $this->nextPrayer = $prayer;
                    break;
                }
            }
        }
        
        if (empty($this->nextPrayer)) {
            $this->nextPrayer = 'subuh';
        }
    }

    public function startAdhan($prayerName)
    {
        $this->displayMode = 'adhan';
        $this->nextPrayer = $prayerName;

        $soundPath = $this->settings['adhan_sound_path'] ?? '/sounds/adhan.mp3';
        $this->dispatchBrowserEvent('play-sound', ['sound' => $soundPath]);

        $iqamahDuration = (int)($this->settings['iqamah_duration_' . $prayerName] ?? 10);
        $this->countdownTimer = $iqamahDuration * 60;
        
        $this->emit('startCountdownAfter', 120000);
    }
    
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
            $this->dispatchBrowserEvent('play-sound', ['sound' => $reminderSound]);
        } else {
            $this->countdownTimer--;
            $minutes = floor($this->countdownTimer / 60);
            $seconds = $this->countdownTimer % 60;
            $this->countdownTimerFormatted = sprintf('%02d:%02d', $minutes, $seconds);
        }
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
        
        $this->findNextPrayer();

        if ($this->displayMode == 'normal') {
            $nowFormatted = $now->format('H:i');
            if (!empty($this->prayerTimes) && in_array($nowFormatted, $this->prayerTimes)) {
                $prayerName = array_search($nowFormatted, $this->prayerTimes);
                $this->startAdhan($prayerName);
                return;
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