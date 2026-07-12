@php
    $heroSlides = $berita_terkini->map(fn ($item) => [
        'image' => asset('storage/romadan_gambar_web/' . $item->image),
        'title' => $item->judul,
        'url' => route('berita-fe', $item->slug),
    ])->values();
@endphp

@if ($heroSlides->isEmpty())
    <section class="relative flex min-h-[62vh] items-center justify-center overflow-hidden bg-navy-950">
        <div class="relative z-10 mx-auto max-w-2xl px-6 text-center">
            <span class="fe-eyebrow justify-center text-gold-400">Berita Terkini</span>
            <h1 class="mt-4 text-3xl font-extrabold text-white sm:text-4xl">Belum ada berita terkini</h1>
            <p class="mt-3 text-slate-300">Konten akan segera hadir. Hubungi admin untuk informasi lebih lanjut.</p>
            <a href="mailto:kemenkeu.prime@kemenkeu.go.id" class="fe-btn fe-btn-primary mt-6">
                Hubungi Admin <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </section>
@else
    <section x-data="{
            slides: @js($heroSlides),
            active: 0,
            timer: null,
            start() { this.timer = setInterval(() => this.next(), 6000); },
        }" x-init="start()"
        class="group relative min-h-[62vh] overflow-hidden bg-navy-950 sm:min-h-[72vh]">

        <template x-for="(slide, i) in slides" :key="i">
            <div x-show="active === i" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0">
                <img :src="slide.image" :alt="slide.title" class="h-full w-full object-cover" loading="eager">
                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/70 to-navy-950/30"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-navy-950/80 via-navy-950/20 to-transparent"></div>
            </div>
        </template>

        <div class="fe-container relative z-10 flex min-h-[62vh] flex-col justify-end pb-16 sm:min-h-[72vh] sm:justify-center sm:pb-0">
            <span class="fe-eyebrow text-gold-400">Berita Terkini</span>

            <template x-for="(slide, i) in slides" :key="'t' + i">
                <h1 x-show="active === i" x-transition.opacity.duration.500ms
                    class="mt-4 max-w-2xl text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl" x-text="slide.title"></h1>
            </template>

            <div class="mt-7">
                <template x-for="(slide, i) in slides" :key="'a' + i">
                    <a x-show="active === i" :href="slide.url" class="fe-btn fe-btn-primary">
                        Baca Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </template>
            </div>
        </div>

        {{-- Dots --}}
        <div class="absolute right-6 bottom-6 z-10 flex gap-2 sm:right-10 sm:bottom-10" x-show="slides.length > 1">
            <template x-for="(slide, i) in slides" :key="'d' + i">
                <button type="button" @click="active = i" :aria-current="active === i"
                    :class="active === i ? 'w-7 bg-gold-500' : 'w-2 bg-white/40 hover:bg-white/70'"
                    class="h-2 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </section>
@endif
