{{-- Partial bersama untuk halaman index publikasi per-tipe (berita/warta/artikel).
     Menerima: $items, $isSearch, $searchValue, $kategori, $tipeLabel, $tipeDesc,
               $indexRoute, $kategoriRoute, $searchParam, $listPartial --}}

<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Publikasi</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">{{ $tipeLabel }}</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">{{ $tipeDesc }}</p>
        </div>

        <div x-data="liveSearch({
                url: @js(route($indexRoute)),
                param: @js($searchParam),
                json: true,
                responseType: 'json',
                target: '#{{ $searchParam }}-results',
                initialValue: @js($searchValue ?? ''),
            })" class="mt-10">
            <div class="relative mx-auto max-w-xl">
                <input type="text" x-model="q" @keydown.enter.prevent="submitNow" placeholder="Cari {{ strtolower($tipeLabel) }}..."
                    maxlength="255"
                    class="w-full rounded-full border border-slate-200 py-3 pr-14 pl-5 text-sm text-slate-700 shadow-[var(--shadow-soft)] transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100 focus:outline-none dark:border-white/10 dark:bg-navy-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:ring-brand-500/20">
                <button type="button" @click="submitNow" class="absolute top-1/2 right-2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-brand-700 text-white transition hover:bg-brand-800">
                    <i class="fa-solid fa-magnifying-glass text-xs" x-show="!loading"></i>
                    <i class="fa-solid fa-spinner animate-spin text-xs" x-show="loading" x-cloak></i>
                </button>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
                <a href="{{ route($indexRoute) }}" class="fe-pill {{ request()->routeIs($indexRoute) ? 'is-active' : '' }}">
                    Semua
                </a>
                @foreach ($kategori as $item)
                    <a href="{{ route($kategoriRoute, strtolower($item->nama_kategori)) }}" class="fe-pill">
                        {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                    </a>
                @endforeach
            </div>

            <div id="{{ $searchParam }}-results" class="mt-10 transition-opacity" :class="loading ? 'opacity-50' : ''">
                @include($listPartial)
            </div>
        </div>
    </div>
</section>
