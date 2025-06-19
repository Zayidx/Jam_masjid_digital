// zayidx/jam_masjid_digital/Jam_masjid_digital-952dd6084a61afc378bb36d0532d50545135b829/resources/views/livewire/admin/dashboard/simulation-controls.blade.php
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Simulasi Tampilan TV
        </h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Gunakan tombol ini untuk menguji mode Adzan & Iqamah pada tampilan TV secara langsung.
        </p>

        @if (session()->has('simulation_message'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative my-4" role="alert">
                <span class="block sm:inline">{{ session('simulation_message') }}</span>
            </div>
        @endif

        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            <button wire:click="simulate('subuh')" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Simulasi Subuh
            </button>
            <button wire:click="simulate('dzuhur')" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Simulasi Dzuhur
            </button>
            <button wire:click="simulate('ashar')" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Simulasi Ashar
            </button>
            <button wire:click="simulate('maghrib')" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Simulasi Maghrib
            </button>
            <button wire:click="simulate('isya')" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Simulasi Isya
            </button>
        </div>
    </div>
</div>