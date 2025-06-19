<div class="h-screen w-screen flex flex-col bg-white text-gray-800 relative overflow-hidden"
     style="background-image: {{ isset($settings['background_image']) ? 'url(' . Storage::url($settings['background_image']) . ')' : 'none' }}; 
            background-size: cover; 
            background-position: center;"
     wire:poll.1s="tick">
    
    <div class="absolute inset-0 bg-white/90 backdrop-blur-sm"></div>
    
    @if($isAsleep)
        <div class="h-screen w-screen bg-gray-100 flex items-center justify-center relative z-10">
            <div class="text-center animate-fade-in">
                <div class="w-16 h-16 border-4 border-gray-300 border-t-green-500 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-gray-600 text-2xl font-light">Mode Hemat Daya Aktif</p>
            </div>
        </div>
    @else
        <header class="relative z-10 px-10  shadow-lg border-b-4 border-green-500 p-6 animate-slide-down">
            <div class="flex justify-between items-center  mx-auto">
                <div class="flex items-center space-x-6">
                    @if(isset($settings['mosque_logo']))
                        <div class="relative">
                            <img src="{{ Storage::url($settings['mosque_logo']) }}" 
                                 alt="Logo Masjid" 
                                 class="h-20 w-20 object-contain rounded-full border-4 border-green-500 p-2 bg-green-50 hover:scale-105 transition-transform duration-300">
                        </div>
                    @endif
                    <div class="space-y-1">
                        <h1 class="text-4xl font-bold text-green-700 animate-slide-right">
                            {{ $settings['mosque_name'] ?? 'Nama Masjid' }}
                        </h1>
                        <div class="flex items-center text-gray-600 text-lg animate-slide-right-delay">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $settings['mosque_address'] ?? 'Alamat Masjid' }}
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 shadow-lg">
                        <div x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }" 
                             x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)" 
                             class="text-5xl font-mono font-bold text-green-700 digital-clock">
                            <span x-text="time"></span>
                        </div>
                    </div>
                <div class="d-flex text-right space-y-2 animate-slide-left">
                   
                    <div class="text-3xl text-gray-700 space-y-1">
                        <div class="font-semibold">{{ $currentDate['masehi'] ?? '' }}</div>
                        <div class="text-green-600 font-arabic">{{ $currentDate['hijriah'] ?? '' }}</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow flex p-6 space-x-6 relative z-10   w-full">
            <div class="w-4/12 animate-slide-right-slow">
                <div class=" rounded-2xl shadow-xl border-2 border-green-100 p-6 ">
                    <h2 class="text-3xl font-bold mb-6 text-center text-green-700 animate-fade-in-delay">
                        Jadwal Sholat Hari Ini
                    </h2>

                    <div class="space-y-3">
                        @if(!empty($prayerTimes))
                            @foreach($prayerTimes as $name => $time)
                            <div class="prayer-time-item transition-all duration-500 {{ $nextPrayer == $name ? 'next-prayer' : 'regular-prayer' }}"
                                 style="animation-delay: {{ $loop->index * 0.1 }}s">
                                @if($nextPrayer == $name)
                                    <div class="bg-green-500 text-white p-7 rounded-xl shadow-lg border-l-8 border-green-600 transform scale-105">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-4 h-4 bg-white rounded-full animate-pulse"></div>
                                                <div>
                                                    <p class="text-sm font-medium opacity-90">SHOLAT SELANJUTNYA</p>
                                                    <p class="text-3xl font-bold capitalize">{{ ucfirst($name) }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-4xl font-mono font-bold">{{ $time }}</p>
                                                <div x-data="prayerCountdown({{ $nextPrayerTimestamp }})" x-init="start()" class="mt-1">
                                                    <span class="text-lg font-mono px-2 py-1 bg-white/20 rounded" x-text="display" wire:ignore></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 hover:bg-green-50 p-5 rounded-lg border border-gray-200 hover:border-green-200 transition-all duration-300">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-4xl text-gray-700 capitalize">{{ ucfirst($name) }}</span>
                                            <span class="text-4xl font-mono font-bold text-gray-800">{{ $time }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-red-500 bg-red-50 p-6 rounded-lg border border-red-200 animate-pulse">
                                <svg class="w-12 h-12 mx-auto mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="font-semibold">Gagal memuat jadwal sholat</p>
                                <p class="text-sm text-red-400 mt-1">Mencoba mengambil data kembali...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="w-8/12 animate-slide-left-slow">
                <div class=" rounded-2xl shadow-xl border-2 border-green-100  flex items-center justify-center overflow-hidden">
                    @if($displayMode == 'normal')
                        @if($currentItem)
                            <div class="w-full h-full flex items-center justify-center animate-fade-in p-8">
                                @if($currentItem->type == 'image')
                                    <img src="{{ Storage::url($currentItem->file_path) }}" 
                                         class="max-h-full max-w-full object-contain rounded-lg shadow-lg animate-zoom-in" 
                                         alt="Galeri Masjid">
                                @elseif($currentItem->type == 'video')
                                    <video src="{{ Storage::url($currentItem->file_path) }}" 
                                           class="max-h-full max-w-full object-contain rounded-lg shadow-lg animate-zoom-in" 
                                           autoplay muted loop></video>
                                @elseif($currentItem->type == 'quran' || $currentItem->type == 'hadith')
                                    <div class="text-center space-y-8 max-w-4xl">
                                        @if(isset($currentItem->text_ar))
                                            <div class="bg-green-50 p-8 rounded-xl border-2 border-green-200 animate-slide-up">
                                                <p class="text-4xl leading-relaxed font-arabic text-green-800 mb-6" dir="rtl">
                                                    {{ $currentItem->text_ar }}
                                                </p>
                                            </div>
                                        @endif
                                        <p class="text-2xl italic text-gray-700 leading-relaxed animate-slide-up-delay bg-gray-50 p-6 rounded-lg">
                                            "{{ $currentItem->text_id }}"
                                        </p>
                                        <p class="text-xl font-semibold text-green-600 bg-green-100 px-6 py-3 rounded-full inline-block animate-slide-up-delay-2">
                                            ({{ $currentItem->source }})
                                        </p>
                                    </div>
                                @elseif($currentItem->type == 'qris')
                                    <div class="text-center animate-zoom-in">
                                        <h2 class="text-3xl font-bold mb-6 text-green-700">SCAN UNTUK INFAQ & SEDEKAH</h2>
                                        <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-green-200 inline-block">
                                            <img src="{{ Storage::url($currentItem->file_path) }}" 
                                                 class="max-h-80 mx-auto" 
                                                 alt="QRIS Donasi">
                                        </div>
                                        <p class="mt-4 text-xl font-semibold text-gray-700">{{ $settings['donation_account_name'] ?? 'Rekening DKM' }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center animate-fade-in">
                                <h1 class="text-4xl font-bold text-green-700 mb-4">Selamat Datang</h1>
                                <p class="text-2xl text-gray-600">{{ $settings['mosque_name'] ?? 'Masjid Kami' }}</p>
                            </div>
                        @endif
                    @elseif($displayMode == 'adhan')
                        <div class="text-center space-y-8 animate-zoom-in p-8">
                            <div class="bg-green-500 text-white p-8 rounded-2xl shadow-2xl animate-pulse-glow">
                                <h1 class="text-6xl font-bold mb-4">Telah Masuk Waktu Sholat</h1>
                                <h2 class="text-8xl font-bold capitalize animate-scale-pulse">{{ ucfirst($nextPrayer) }}</h2>
                                <h3 class="text-3xl mt-4 opacity-90">Untuk Wilayah {{ $settings['mosque_city_name'] ?? 'Sekitarnya' }}</h3>
                            </div>
                        </div>
                    @elseif($displayMode == 'countdown')
                        <div class="text-center space-y-8 animate-zoom-in p-8">
                            <div class="bg-red-50 border-2 border-red-200 p-8 rounded-2xl">
                                <h1 class="text-5xl font-bold text-red-600 mb-6">HITUNG MUNDUR IQAMAH</h1>
                                <p class="text-8xl font-mono font-bold text-red-700 animate-scale-pulse mb-6">{{ $countdownTimerFormatted }}</p>
                                <p class="text-3xl font-semibold text-green-600 animate-pulse-glow">LURUSKAN DAN RAPATKAN SHAF</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <footer class="relative z-10 bg-green-500 text-white p-4 overflow-hidden animate-slide-up">
            <div class="flex items-center max-w-7xl mx-auto">
                <svg class="w-6 h-6 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M8.464 8.464a5 5 0 000 7.072M5.636 5.636a9 9 0 000 12.728"></path>
                </svg>
                <div class="flex-1 overflow-hidden">
                    <div class="whitespace-nowrap text-xl py-2 font-medium running-text" 
                         style="animation: marquee {{ $settings['running_text_speed'] ?? 45 }}s linear infinite;">
                        @if($runningTexts->isNotEmpty())
                            @foreach($runningTexts as $text)
                                <span class="mx-8">{{ $text->content }}</span>
                            @endforeach
                        @else
                            <span class="mx-4">Selamat datang di {{ $settings['mosque_name'] ?? 'Masjid Kami' }}. Semoga Allah SWT menerima amal ibadah kita semua.</span>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
        
        @if(isset($settings['qris_display_mode']) && $settings['qris_display_mode'] == 'corner' && isset($settings['qris_image_path']))
            <div class="absolute bottom-24 right-6 z-20 animate-bounce-in">
                <div class="bg-white p-4 rounded-2xl shadow-2xl border-2 border-green-500 hover:scale-110 transition-transform duration-300">
                    <img src="{{ Storage::url($settings['qris_image_path']) }}" 
                         class="w-32 h-32 object-contain" 
                         alt="QRIS">
                    <p class="text-xs text-green-700 text-center font-bold mt-2">
                        SCAN INFAQ
                    </p>
                </div>
            </div>
        @endif
    @endif
</div>



@push('scripts')
<script>
    // --- PENAMBAHAN: Fungsi JavaScript untuk menangani countdown ---
    function prayerCountdown(targetTimestamp) {
        return {
            display: '00:00:00',
            target: targetTimestamp,
            interval: null,
            start() {
                if (!this.target) {
                    this.display = '-';
                    return;
                }

                const updateTimer = () => {
                    const now = Math.floor(Date.now() / 1000);
                    let secondsLeft = this.target - now;

                    if (secondsLeft <= 0) {
                        this.display = '00:00:00';
                        clearInterval(this.interval);
                        // Livewire's poll will handle the state change automatically
                        return;
                    }

                    let hours = Math.floor(secondsLeft / 3600);
                    secondsLeft %= 3600;
                    let minutes = Math.floor(secondsLeft / 60);
                    let seconds = secondsLeft % 60;

                    this.display = 
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');
                };
                
                updateTimer(); // Panggil sekali di awal agar tidak ada jeda
                this.interval = setInterval(updateTimer, 1000);
            }
        }
    }

    document.addEventListener('livewire:navigated', function () {
        // Smooth content transitions
        // ... (kode observer tetap sama)

        // Enhanced audio handling
        window.addEventListener('play-sound', event => {
            let audio = new Audio(event.detail[0].sound);
            let volumeSetting = {{ $settings['master_volume'] ?? 100 }};
            audio.volume = volumeSetting / 100;
            audio.play().catch(e => console.error("Pemutaran audio gagal:", e));
        });

        // Countdown with visual feedback
        window.addEventListener('start-countdown-after', event => {
            let delay = event.detail[0].delay;
            setTimeout(() => {
                @this.call('startCountdown');
            }, delay);
        });

        // Smooth reload
        window.addEventListener('force-reload', event => {
            console.log('Menerima sinyal reboot dari server. Memuat ulang dalam 3 detik...');
            document.body.style.transition = 'opacity 0.5s ease-out';
            document.body.style.opacity = '0.8';
            
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        });
    });

    // Smooth page load
    document.addEventListener('DOMContentLoaded', function() {
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.3s ease-in';
        
        setTimeout(() => {
            document.body.style.opacity = '1';
        }, 50);
    });
</script>
@endpush