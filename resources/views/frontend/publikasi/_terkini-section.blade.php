{{-- Menerima: $items, $label, $eyebrow, $detailRoute, $indexRoute, $bg --}}
<section class="{{ $bg ?? 'bg-white' }} py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="flex flex-col items-center gap-2 text-center">
            <span class="fe-eyebrow">{{ $eyebrow }}</span>
            <h2 class="text-2xl font-extrabold text-navy-800 sm:text-3xl dark:text-white">{{ $label }}</h2>
        </div>

        @if ($items->isEmpty())
            <p class="mt-8 text-center text-slate-500 dark:text-slate-400">Belum ada {{ strtolower($label) }}.</p>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($items as $item)
                    <article class="fe-card">
                        <a href="{{ route($detailRoute, $item->slug) }}" class="block aspect-[16/10] overflow-hidden">
                            <img src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" alt="{{ $item->judul }}"
                                class="h-full w-full object-cover transition duration-500 hover:scale-105" loading="lazy">
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                                <span>{{ \Carbon\Carbon::parse($item->tanggal_terbit)->translatedFormat('j F Y') }}</span>
                                <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                <span class="font-semibold text-brand-700 dark:text-brand-400">
                                    {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                                </span>
                                <span class="ml-auto inline-flex items-center gap-1"><i class="fa-regular fa-eye"></i>{{ $item->views }}</span>
                            </div>
                            <a href="{{ route($detailRoute, $item->slug) }}" title="{{ $item->judul }}"
                                class="mt-3 line-clamp-2 text-base font-bold text-navy-800 transition hover:text-brand-700 dark:text-white dark:hover:text-brand-400">
                                {{ Str::limit($item->judul, 20, '...') }}
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex justify-center">
            <a href="{{ route($indexRoute) }}" class="fe-btn fe-btn-ghost">
                Lihat Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</section>
