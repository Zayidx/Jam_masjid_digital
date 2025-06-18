{{--
    File: resources/views/livewire/display-cdn.blade.php
    Deskripsi: Versi yang menggunakan Tailwind CSS via CDN dan kustomisasi manual.
--}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Masjid</title>
    
    {{-- 
        LANGKAH 1: MEMASANG TAILWIND CSS VIA CDN
        Script ini akan memuat semua utility class standar dari Tailwind.
        Ini menggantikan kebutuhan untuk file `tailwind.config.js` dan proses build.
    --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{--
        LANGKAH 2: KUSTOMISASI MANUAL & IMPORT FONT
        Karena kita tidak bisa menggunakan `tailwind.config.js`, kita definisikan kustomisasi di sini.
    --}}
    {{-- 1. Mengimpor font dari Google Fonts (Sama seperti sebelumnya) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- 2. Menambahkan CSS kustom menggunakan tag <style> --}}
    <style>
        /* Mendefinisikan font-family default agar lebih konsisten */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Membuat ulang class `.font-arabic` yang sebelumnya ada di config.
            Sekarang setiap elemen dengan class ini akan menggunakan font Noto Naskh Arabic.
        */
        .font-arabic {
            font-family: 'Noto Naskh Arabic', serif;
        }

        /* Membuat ulang animasi marquee (`@keyframes`) yang sebelumnya ada di config.
            Ini mendefinisikan animasi yang bergerak dari kanan (100%) ke kiri (-100%).
        */
        @keyframes marquee {
          '0%': { transform: 'translateX(100%)' },
          '100%': { transform: 'translateX(-100%)' },
        }

        /* Membuat ulang class `.animate-marquee` yang sebelumnya ada di config.
            Class ini menerapkan animasi 'marquee' yang sudah kita definisikan di atas.
            Animasi berjalan selama 45 detik, secara linear, dan berulang tanpa henti.
        */
        .animate-marquee {
            animation: marquee 45s linear infinite;
        }
    </style>
    
    {{-- Kode Livewire Styles --}}
    @livewireStyles
</head>
<body>

{{--
    Elemen div utama. Semua class Tailwind di sini akan berfungsi karena script CDN di atas.
    - `font-sans`: Menggunakan font family 'Inter' yang kita set di <style>.
    - `antialiased`: Menghaluskan render font.
--}}
<div class="h-screen w-screen flex flex-col bg-cover bg-center font-sans antialiased"
     style="background-image: url('{{ isset($settings['background_image']) ? Storage::url($settings['background_image']) : '' }}'); background-color: {{ $settings['theme_bg_color'] ?? '#052e16' }}; color: {{ $settings['theme_text_color'] ?? '#f0fdf4' }};"
     wire:poll.1s="tick">
    
    {{-- Bagian ini sama persis dengan kode sebelumnya, tidak ada perubahan class yang diperlukan --}}
    @if($isAsleep)
        <div class="h-screen w-screen bg-black flex items-center justify-center">
            <p class="text-gray-500 text-3xl font-medium">Mode Hemat Daya Aktif</p>
        </div>
    @else
        <header class="p-5 flex justify-between items-center bg-gradient-to-b from-black/60 to-transparent">
            <div class="flex items-center">
                @if(isset($settings['mosque_logo']))
                    <img src="{{ Storage::url($settings['mosque_logo']) }}" alt="Logo Masjid" class="h-20 w-20 object-contain mr-5 drop-shadow-lg">
                @endif
                <div>
                    <h1 class="text-4xl font-bold tracking-tight drop-shadow-md">{{ $settings['mosque_name'] ?? 'Nama Masjid' }}</h1>
                    <p class="text-xl text-white/80 drop-shadow-md">{{ $settings['mosque_address'] ?? 'Alamat Masjid' }}</p>
                </div>
            </div>
            <div class="text-right">
                <div x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)" class="text-6xl font-bold drop-shadow-lg">
                    <span x-text="time"></span>
                </div>
                <div class="text-2xl text-white/90 mt-1 drop-shadow-md">
                    <span>{{ $currentDate['masehi'] ?? '' }}</span>
                    <span class="mx-2">|</span>
                    <span>{{ $currentDate['hijriah'] ?? '' }}</span>
                </div>
            </div>
        </header>

        <main class="flex-grow flex p-5 space-x-5 overflow-hidden">
            {{--
                Kolom kiri untuk jadwal sholat.
                - `backdrop-blur-sm`: Efek blur ini mungkin tidak didukung oleh CDN Tailwind standar.
                  Jika tidak berfungsi, Anda bisa menghapusnya. Dukungan tergantung versi CDN.
                  (Tailwind Play CDN yang baru mendukung ini).
            --}}
            <div class="w-1/3 bg-black/25 rounded-2xl p-6 flex flex-col backdrop-blur-sm">
                <h2 class="text-3xl font-bold mb-6 text-center border-b-2 border-white/20 pb-4">Jadwal Sholat Hari Ini</h2>
                <ul class="space-y-4 text-3xl flex-grow">
                    @if(!empty($prayerTimes))
                        @foreach($prayerTimes as $name => $time)
                        <li class="flex justify-between items-center p-4 rounded-xl transition-all duration-300 ease-in-out {{ $nextPrayer == $name ? 'bg-green-400 text-green-950 scale-105 shadow-2xl' : 'bg-white/10 hover:bg-white/20' }}">
                            <span class="font-semibold">{{ ucfirst($name) }}</span>
                            <span class="font-bold">{{ $time }}</span>
                        </li>
                        @endforeach
                    @else
                        <li class="text-center pt-10 text-white/70">Memuat jadwal...</li>
                    @endif
                </ul>
            </div>

            <div class="w-2/3 bg-black/25 rounded-2xl flex items-center justify-center text-center overflow-hidden backdrop-blur-sm">
                @if($displayMode == 'normal')
                    @if($currentItem)
                        <div class="w-full h-full flex items-center justify-center p-4">
                            @if($currentItem->type == 'image')
                                <img src="{{ Storage::url($currentItem->file_path) }}" class="max-h-full max-w-full object-contain rounded-lg shadow-xl" alt="Galeri Masjid">
                            @elseif($currentItem->type == 'video')
                                <video src="{{ Storage::url($currentItem->file_path) }}" class="max-h-full max-w-full object-contain rounded-lg shadow-xl" autoplay muted loop></video>
                            @elseif($currentItem->type == 'quran' || $currentItem->type == 'hadith')
                                <div class="p-10 flex flex-col justify-center max-w-4xl mx-auto">
                                    @if(isset($currentItem->text_ar))
                                        {{-- 
                                            Class `font-arabic` ini sekarang akan berfungsi karena kita definisikan manual di tag <style> di atas.
                                        --}}
                                        <p class="text-5xl leading-relaxed font-arabic mb-8" dir="rtl">{{ $currentItem->text_ar }}</p>
                                    @endif
                                    <p class="text-3xl italic text-white/90">"{{ $currentItem->text_id }}"</p>
                                    <p class="text-2xl mt-8 font-semibold text-white/70">({{ $currentItem->source }})</p>
                                </div>
                            @elseif($currentItem->type == 'qris')
                                <div>
                                    <h2 class="text-4xl font-bold mb-6 tracking-wide">INFAQ & SEDEKAH</h2>
                                    <img src="{{ Storage::url($currentItem->file_path) }}" class="bg-white p-4 rounded-xl max-h-[28rem] mx-auto shadow-2xl" alt="QRIS Donasi">
                                    <p class="mt-6 text-3xl font-semibold">{{ $settings['donation_account_name'] ?? 'Rekening DKM' }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <h1 class="text-5xl font-bold drop-shadow-lg">Selamat Datang di {{ $settings['mosque_name'] ?? 'Masjid Kami' }}</h1>
                    @endif
                @elseif($displayMode == 'adhan')
                    <div class="p-8 animate-pulse">
                        <h1 class="text-8xl font-bold tracking-tight">Telah Masuk Waktu Sholat</h1>
                        <h2 class="text-9xl font-extrabold mt-6 uppercase">{{ ucfirst($nextPrayer) }}</h2>
                        <h3 class="text-5xl mt-4 text-white/80">Untuk Wilayah {{ $settings['mosque_city_name'] ?? 'Sekitarnya' }}</h3>
                    </div>
                @elseif($displayMode == 'countdown')
                    <div class="p-8">
                        <h1 class="text-8xl font-bold tracking-tight">HITUNG MUNDUR IQAMAH</h1>
                        <p class="text-9xl font-mono font-bold my-10 text-yellow-300">{{ $countdownTimerFormatted }}</p>
                        <p class="text-6xl font-semibold animate-pulse uppercase">Luruskan dan Rapatkan Shaf</p>
                    </div>
                @endif
            </div>
        </main>

        <footer class="bg-black/40 p-3 overflow-hidden">
            <div class="relative flex">
                 {{-- 
                    Class `animate-marquee` ini sekarang akan berfungsi karena kita definisikan manual di tag <style> di atas.
                 --}}
                <div class="animate-marquee whitespace-nowrap text-3xl py-2">
                    @if($runningTexts->isNotEmpty())
                        @foreach($runningTexts as $text)
                            <span class="mx-12">{{ $text->content }}</span>
                        @endforeach
                    @else
                        <span class="mx-4">Selamat datang di {{ $settings['mosque_name'] ?? 'Masjid Kami' }}. Semoga Allah SWT menerima amal ibadah kita semua.</span>
                    @endif
                </div>
            </div>
        </footer>
        
        {{-- Sisa kode lainnya tetap sama --}}
        @if(isset($settings['qris_display_mode']) && $settings['qris_display_mode'] == 'corner' && isset($settings['qris_image_path']))
            <div class="absolute bottom-28 right-8 bg-white p-2 rounded-lg shadow-2xl transition-all hover:scale-110">
                <img src="{{ Storage::url($settings['qris_image_path']) }}" class="w-36 h-36 object-contain" alt="QRIS Infaq">
                <p class="text-sm text-black text-center font-bold mt-1 uppercase">Scan Infaq</p>
            </div>
        @endif
    @endif
</div>

{{-- Kode Livewire & AlpineJS Scripts --}}
@livewireScripts
{{-- Anda mungkin perlu AlpineJS juga jika belum ada --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Stack 'scripts' dari kode Anda sebelumnya, tidak perlu diubah. --}}
@stack('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('startCountdownAfter', (delay) => {
            setTimeout(() => {
                @this.call('startCountdown');
            }, delay);
        });
    });
    window.addEventListener('play-sound', event => {
        let audio = new Audio(event.detail.sound);
        let volumeSetting = {{ $settings['master_volume'] ?? 100 }};
        audio.volume = volumeSetting / 100;
        audio.play().catch(e => console.error("Audio playback failed:", e));
    });
    window.addEventListener('force-reload', event => {
        console.log('Menerima sinyal reboot dari server. Memuat ulang dalam 3 detik...');
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    });
</script>

</body>
</html>