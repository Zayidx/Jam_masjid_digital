<?php

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
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        session()->flash('message', 'Pengaturan berhasil disimpan.');
        // PERUBAHAN DARI EMIT KE DISPATCH
        $this->dispatch('settingsUpdated');
    }

    public function forceReboot()
    {
        // PERUBAHAN DARI EMIT KE DISPATCH
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