<div class="h-screen w-screen flex flex-col bg-cover bg-center" 
     style="background-image: url('{{ isset($settings['background_image']) ? Storage::url($settings['background_image']) : '' }}'); background-color: {{ $settings['theme_bg_color'] ?? '#052e16' }}; color: {{ $settings['theme_text_color'] ?? '#ffffff' }};"
     wire:poll.1s="tick">
    
    @if($isAsleep)
        <div class="h-screen w-screen bg-black flex items-center justify-center">
            <p class="text-gray-700 text-2xl">Mode Hemat Daya Aktif</p>
        </div>
    @else
        <header class="p-4 flex justify-between items-center bg-black bg-opacity-30">
            <div class="flex items-center">
                @if(isset($settings['mosque_logo']))
                    <img src="{{ Storage::url($settings['mosque_logo']) }}" alt="Logo" class="h-16 w-16 object-contain mr-4">
                @endif
                <div>
                    <h1 class="text-3xl font-bold">{{ $settings['mosque_name'] ?? 'Nama Masjid' }}</h1>
                    <p class="text-lg">{{ $settings['mosque_address'] ?? 'Alamat Masjid' }}</p>
                </div>
            </div>
            <div class="text-right">
                <div x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)" class="text-5xl font-bold">
                    <span x-text="time"></span>
                </div>
                <div class="text-xl">
                    <span>{{ $currentDate['masehi'] ?? '' }}</span> | <span>{{ $currentDate['hijriah'] ?? '' }}</span>
                </div>
            </div>
        </header>

        <main class="flex-grow flex p-4 space-x-4 overflow-hidden">
            <div class="w-1/3 bg-black bg-opacity-20 rounded-lg p-4 flex flex-col">
                <h2 class="text-2xl font-bold mb-4 text-center">Jadwal Sholat Hari Ini</h2>
                <ul class="space-y-3 text-2xl flex-grow">
                    @if(!empty($prayerTimes))
                        @foreach($prayerTimes as $name => $time)
                        <li class="flex justify-between p-3 rounded-lg transition-all duration-500 {{ $nextPrayer == $name ? 'bg-yellow-500 text-black scale-105' : 'bg-white bg-opacity-10' }}">
                            <span class="font-semibold">{{ ucfirst($name) }}</span>
                            <span>{{ $time }}</span>
                        </li>
                        @endforeach
                    @else
                        <li class="text-center">Memuat jadwal...</li>
                    @endif
                </ul>
            </div>

            <div class="w-2/3 bg-black bg-opacity-20 rounded-lg flex items-center justify-center text-center overflow-hidden">
                @if($displayMode == 'normal')
                    @if($currentItem)
                        <div class="w-full h-full flex items-center justify-center">
                            @if($currentItem->type == 'image')
                                <img src="{{ Storage::url($currentItem->file_path) }}" class="max-h-full max-w-full object-contain" alt="Galeri Masjid">
                            @elseif($currentItem->type == 'video')
                                <video src="{{ Storage::url($currentItem->file_path) }}" class="max-h-full max-w-full object-contain" autoplay muted loop></video>
                            @elseif($currentItem->type == 'quran' || $currentItem->type == 'hadith')
                                <div class="p-8">
                                    @if(isset($currentItem->text_ar))
                                        <p class="text-4xl leading-relaxed font-arabic mb-6" dir="rtl">{{ $currentItem->text_ar }}</p>
                                    @endif
                                    <p class="text-2xl italic">"{{ $currentItem->text_id }}"</p>
                                    <p class="text-xl mt-6 font-semibold">({{ $currentItem->source }})</p>
                                </div>
                            @elseif($currentItem->type == 'qris')
                                <div>
                                    <h2 class="text-3xl font-bold mb-4">SCAN UNTUK INFAQ & SEDEKAH</h2>
                                    <img src="{{ Storage::url($currentItem->file_path) }}" class="bg-white p-4 rounded-lg max-h-96 mx-auto" alt="QRIS Donasi">
                                    <p class="mt-4 text-2xl font-semibold">{{ $settings['donation_account_name'] ?? 'Rekening DKM' }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <h1 class="text-4xl">Selamat Datang di {{ $settings['mosque_name'] ?? 'Masjid Kami' }}</h1>
                    @endif
                @elseif($displayMode == 'adhan')
                    <div class="p-8 animate-pulse">
                        <h1 class="text-7xl font-bold">Telah Masuk Waktu Sholat</h1>
                        <h2 class="text-9xl font-bold mt-4">{{ ucfirst($nextPrayer) }}</h2>
                        <h3 class="text-4xl mt-2">Untuk Wilayah {{ $settings['mosque_city_name'] ?? 'Sekitarnya' }}</h3>
                    </div>
                @elseif($displayMode == 'countdown')
                    <div class="p-8">
                        <h1 class="text-7xl font-bold">HITUNG MUNDUR IQAMAH</h1>
                        <p class="text-9xl font-mono font-bold my-8">{{ $countdownTimerFormatted }}</p>
                        <p class="text-5xl font-semibold animate-pulse">LURUSKAN DAN RAPATKAN SHAF</p>
                    </div>
                @endif
            </div>
        </main>

        <footer class="bg-black bg-opacity-40 p-3 overflow-hidden">
            <div class="relative flex">
                <div class="animate-marquee whitespace-nowrap text-2xl py-2">
                    @if($runningTexts->isNotEmpty())
                        @foreach($runningTexts as $text)
                            <span class="mx-8">+++ {{ $text->content }} +++</span>
                        @endforeach
                    @else
                        <span class="mx-4">Selamat datang di {{ $settings['mosque_name'] ?? 'Masjid Kami' }}. Semoga Allah SWT menerima amal ibadah kita semua.</span>
                    @endif
                </div>
            </div>
        </footer>
        
        @if(isset($settings['qris_display_mode']) && $settings['qris_display_mode'] == 'corner' && isset($settings['qris_image_path']))
            <div class="absolute bottom-24 right-5 bg-white p-2 rounded-lg shadow-2xl transition-all hover:scale-110">
                <img src="{{ Storage::url($settings['qris_image_path']) }}" class="w-32 h-32 object-contain" alt="QRIS">
                <p class="text-xs text-black text-center font-bold mt-1">SCAN INFAQ</p>
            </div>
        @endif
    @endif
</div>

@push('scripts')
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
@endpush