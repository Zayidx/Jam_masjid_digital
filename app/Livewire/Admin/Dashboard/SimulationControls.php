<?php
// zayidx/jam_masjid_digital/Jam_masjid_digital-952dd6084a61afc378bb36d0532d50545135b829/app/Livewire/Admin/Dashboard/SimulationControls.php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Setting;

class SimulationControls extends Component
{
    /**
     * Mengirim sinyal simulasi ke tampilan TV.
     *
     * @param string $prayerName Nama waktu sholat yang akan disimulasikan.
     * @return void
     */
    public function simulate($prayerName)
    {
        // Menyimpan permintaan simulasi ke database dengan format 'nama_sholat|timestamp'
        // Timestamp ditambahkan untuk memastikan setiap permintaan unik dan bisa divalidasi umurnya.
        Setting::updateOrCreate(
            ['key' => 'simulation_request'],
            ['value' => $prayerName . '|' . now()->timestamp]
        );

        // Memberikan notifikasi feedback kepada admin.
        session()->flash('simulation_message', 'Sinyal simulasi untuk ' . ucfirst($prayerName) . ' telah dikirim.');
    }

    public function render()
    {
        return view('livewire.admin.dashboard.simulation-controls');
    }
}