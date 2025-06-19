<?php
// zayidx/jam_masjid_digital/Jam_masjid_digital-952dd6084a61afc378bb36d0532d50545135b829/app/Livewire/Admin/SettingsManager.php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;


class SettingsManager extends Component
{
    use WithFileUploads;

    public $settings = [];
    public $logoUpload;
    public $backgroundUpload;
    public $qrisUpload;

    protected $rules = [
        'settings.mosque_name' => 'required|string|max:255',
        'settings.mosque_address' => 'nullable|string',
        'settings.mosque_city_name' => 'nullable|string',
        // --- PENAMBAHAN: Aturan validasi untuk nama negara ---
        'settings.country_name' => 'required|string',
        'settings.location_city_id' => 'required|string',
        'settings.donation_account_name' => 'nullable|string',
        'settings.theme_bg_color' => 'nullable|string',
        'settings.theme_text_color' => 'nullable|string',
        'settings.master_volume' => 'nullable|numeric|min:0|max:100',
        'settings.qris_display_mode' => 'nullable|string',
        'settings.sleep_time' => 'nullable|date_format:H:i',
        'settings.wake_time' => 'nullable|date_format:H:i',
        'settings.iqamah_duration_subuh' => 'nullable|numeric|min:1',
        'settings.iqamah_duration_dzuhur' => 'nullable|numeric|min:1',
        'settings.iqamah_duration_ashar' => 'nullable|numeric|min:1',
        'settings.iqamah_duration_maghrib' => 'nullable|numeric|min:1',
        'settings.iqamah_duration_isya' => 'nullable|numeric|min:1',
        // --- PENAMBAHAN: Aturan validasi untuk kecepatan teks berjalan ---
        'settings.running_text_speed' => 'nullable|numeric|min:5',
        // --- PENAMBAHAN: Aturan validasi untuk durasi adzan ---
        'settings.adhan_duration_seconds' => 'nullable|numeric|min:30',
        'logoUpload' => 'nullable|image|max:1024',
        'backgroundUpload' => 'nullable|image|max:2048',
        'qrisUpload' => 'nullable|image|max:1024',
        
    ];

    public function mount()
    {
        $this->settings = Setting::pluck('value', 'key')->toArray();
    }

    public function save()
    {
        $this->validate();

        if ($this->logoUpload) {
            $this->settings['mosque_logo'] = $this->logoUpload->store('logos', 'public');
        }

        if ($this->backgroundUpload) {
            $this->settings['background_image'] = $this->backgroundUpload->store('backgrounds', 'public');
        }
        
        if ($this->qrisUpload) {
            $this->settings['qris_image_path'] = $this->qrisUpload->store('qris', 'public');
        }

        foreach ($this->settings as $key => $value) {
            // --- PERUBAHAN: Memastikan nilai null juga disimpan agar bisa mengosongkan setting ---
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        session()->flash('message', 'Pengaturan berhasil disimpan.');
        // --- PERUBAHAN: Menggunakan sintaks dispatch Livewire v3 yang lebih modern ---
        $this->dispatch('settingsUpdated');
    }

    public function forceReboot()
    {
        // --- PERUBAHAN: Menggunakan sintaks dispatch Livewire v3 yang lebih modern ---
        $this->dispatch('forceRebootDisplay');
        session()->flash('message', 'Sinyal reboot telah dikirim ke perangkat TV.');
    }
    
    public function runSystemUpdate()
    {
        if (auth()->id() !== 1) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melakukan aksi ini.');
            return;
        }

        $basePath = base_path();
        $command = "cd $basePath && git pull origin main 2>&1";
        $output = shell_exec($command);

        session()->flash('message', "Hasil Update:<br><pre class='text-sm bg-gray-800 text-white p-4 rounded'>{$output}</pre>");
    }

    public function render()
    {
        return view('livewire.admin.settings-manager');
    }
}