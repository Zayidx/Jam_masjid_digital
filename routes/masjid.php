<?php

use Illuminate\Support\Facades\Route;

// Komponen Livewire untuk Proyek Jam Masjid
use App\Livewire\Display;
use App\Livewire\Admin\SettingsManager;
use App\Livewire\Admin\RunningTextManager;
use App\Livewire\Admin\MediaGalleryManager;
use App\Livewire\Admin\IslamicContentManager;

/*
|--------------------------------------------------------------------------
| Rute Khusus Proyek Jam TV Masjid
|--------------------------------------------------------------------------
*/

// Mengganti rute root '/' dari 'welcome' ke tampilan Jam TV kita
Route::get('/', Display::class);

// Grup untuk semua halaman manajemen admin kita
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', SettingsManager::class)->name('settings');
    Route::get('/running-texts', RunningTextManager::class)->name('running-texts');
    Route::get('/media-gallery', MediaGalleryManager::class)->name('media-gallery');
    Route::get('/islamic-content', IslamicContentManager::class)->name('islamic-content');
});