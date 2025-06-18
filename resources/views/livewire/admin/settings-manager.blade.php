<div>
    <form wire:submit.prevent="save" class="p-4 sm:p-6 lg:p-8 space-y-8">

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{!! session('message') !!}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{!! session('error') !!}</span>
            </div>
        @endif

        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Identitas & Lokasi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Masjid</label>
                    <input type="text" wire:model.defer="settings.mosque_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Singkat Masjid</label>
                    <input type="text" wire:model.defer="settings.mosque_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kota</label>
                    <input type="text" wire:model.defer="settings.mosque_city_name" placeholder="Contoh: DKI Jakarta" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">ID Kota (API MyQuran)</label>
                    <input type="text" wire:model.defer="settings.location_city_id" placeholder="Contoh: 1301 untuk Jakarta" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Logo Masjid</label>
                    <input type="file" wire:model="logoUpload" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if(isset($settings['mosque_logo']) && !$logoUpload)
                        <img src="{{ Storage::url($settings['mosque_logo']) }}" class="w-24 h-24 mt-2 object-contain rounded border p-1">
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Durasi Iqamah (Menit)</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                 <div><label>Subuh</label><input type="number" wire:model.defer="settings.iqamah_duration_subuh" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                 <div><label>Dzuhur</label><input type="number" wire:model.defer="settings.iqamah_duration_dzuhur" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                 <div><label>Ashar</label><input type="number" wire:model.defer="settings.iqamah_duration_ashar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                 <div><label>Maghrib</label><input type="number" wire:model.defer="settings.iqamah_duration_maghrib" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                 <div><label>Isya</label><input type="number" wire:model.defer="settings.iqamah_duration_isya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            </div>
        </div>

        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Kustomisasi Tampilan & Suara</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Warna Latar Utama</label>
                    <input type="color" wire:model.defer="settings.theme_bg_color" class="mt-1 block w-full h-10 rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Warna Teks Utama</label>
                    <input type="color" wire:model.defer="settings.theme_text_color" class="mt-1 block w-full h-10 rounded-md border-gray-300">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Volume Suara (0-100)</label>
                    <input type="range" min="0" max="100" wire:model.defer="settings.master_volume" class="mt-1 block w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mode Tampilan QRIS</label>
                    <select wire:model.defer="settings.qris_display_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="cycle">Bergantian dengan konten lain</option>
                        <option value="corner">Selalu tampil di sudut</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gambar Latar</label>
                    <input type="file" wire:model="backgroundUpload" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Gambar QRIS Donasi</label>
                    <input type="file" wire:model="qrisUpload" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
        </div>
        
         <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Jadwal Tidur Layar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Waktu Mulai Tidur</label>
                    <input type="time" wire:model.defer="settings.sleep_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Waktu Bangun</label>
                    <input type="time" wire:model.defer="settings.wake_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end sticky bottom-0 bg-white bg-opacity-75 backdrop-blur-sm py-4 px-8">
            <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
    
    <div class="p-8">
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Aksi Perangkat</h2>
            <div class="flex space-x-4">
                <button wire:click="forceReboot" type="button" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Reboot Tampilan TV</button>
                <button wire:click="runSystemUpdate" onclick="return confirm('Peringatan: Ini akan menjalankan `git pull` di server. Pastikan tidak ada perubahan lokal yang belum di-commit. Lanjutkan?')" type="button" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">Update Sistem (Git Pull)</button>
            </div>
        </div>
    </div>
</div>