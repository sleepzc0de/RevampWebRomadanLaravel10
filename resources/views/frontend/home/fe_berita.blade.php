@php
    $beritaSlides = $berita_terpopuler->map(fn ($item) => [
        'image' => asset('storage/romadan_gambar_web/' . $item->image),
        'judul' => $item->judul,
        'slug' => $item->slug,
        'url' => route('berita-fe', $item->slug),
        'date' => \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y'),
        'kategori' => strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)),
        'views' => $item->views,
    ])->values();
@endphp

<section class="bg-slate-50 py-20 sm:py-24 dark:bg-navy-950">
    <div class="fe-container">
        <div class="flex flex-col items-center gap-2 text-center">
            <span class="fe-eyebrow">Publikasi</span>
            <h2 class="text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Berita Terpopuler</h2>
        </div>

        @if ($berita_terpopuler->isEmpty())
            <p class="mt-10 text-center text-slate-500 dark:text-slate-400">Belum ada berita terpopuler.</p>
        @else
            <div class="relative mt-12" x-data="newsCarousel(@js($beritaSlides))" x-init="start()"
                @mouseenter="pause()" @mouseleave="resume()">
                <div class="overflow-hidden rounded-2xl">
                    <div class="flex transition-transform duration-700 ease-out"
                        :style="`transform: translateX(-${active * 100}%)`">
                        <template x-for="(slide, i) in slides" :key="i">
                            <a :href="slide.url" :title="slide.judul"
                                class="fe-card w-full flex-none sm:flex-row">
                                <span class="block aspect-[16/10] overflow-hidden sm:w-2/5 sm:flex-none">
                                    <img :src="slide.image" :alt="slide.judul" loading="lazy"
                                        class="h-full w-full object-cover transition duration-500 hover:scale-105">
                                </span>
                                <span class="flex flex-1 flex-col justify-center p-6 sm:p-8">
                                    <span class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                                        <span x-text="slide.date"></span>
                                        <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                        <span class="font-semibold text-brand-700 dark:text-brand-400" x-text="slide.kategori"></span>
                                        <span class="ml-auto inline-flex items-center gap-1"><i class="fa-regular fa-eye"></i><span x-text="slide.views"></span></span>
                                    </span>
                                    <span class="mt-3 line-clamp-2 text-lg font-bold text-navy-800 transition hover:text-brand-700 sm:text-xl dark:text-white dark:hover:text-brand-400"
                                        x-text="slide.judul"></span>
                                    <span class="mt-4 inline-flex w-fit items-center gap-1.5 text-sm font-semibold text-brand-700 dark:text-brand-400">
                                        Baca Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </span>
                                </span>
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Panah navigasi (desktop) --}}
                <button type="button" @click="prev()" x-show="slides.length > 1" aria-label="Sebelumnya"
                    class="absolute top-1/2 left-2 hidden h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-navy-800 shadow-[var(--shadow-soft)] transition hover:bg-white sm:flex dark:bg-navy-800/90 dark:text-white dark:hover:bg-navy-800">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button type="button" @click="next()" x-show="slides.length > 1" aria-label="Berikutnya"
                    class="absolute top-1/2 right-2 hidden h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-navy-800 shadow-[var(--shadow-soft)] transition hover:bg-white sm:flex dark:bg-navy-800/90 dark:text-white dark:hover:bg-navy-800">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>

                {{-- Dots --}}
                <div class="mt-6 flex justify-center gap-2" x-show="slides.length > 1">
                    <template x-for="(slide, i) in slides" :key="'d' + i">
                        <button type="button" @click="go(i)" :aria-current="active === i"
                            :class="active === i ? 'w-7 bg-brand-700 dark:bg-brand-500' : 'w-2 bg-slate-300 hover:bg-slate-400 dark:bg-slate-600 dark:hover:bg-slate-500'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        @endif

        <div class="mt-10 flex justify-center">
            <a href="{{ route('publikasi-index-berita-fe') }}" class="fe-btn fe-btn-ghost">
                Lihat Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</section>
