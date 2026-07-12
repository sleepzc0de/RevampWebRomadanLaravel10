{{-- Partial bersama untuk daftar publikasi (berita/warta/artikel).
     Menerima: $items (paginator), $isSearch, $searchValue, $detailRoute --}}

@if ($isSearch)
    <p class="mb-6 text-center text-sm text-slate-500 dark:text-slate-400">
        Menampilkan hasil pencarian untuk: <span class="font-semibold text-navy-800 dark:text-white">&ldquo;{{ $searchValue }}&rdquo;</span>
    </p>
@endif

@if ($items->count() > 0)
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($items as $item)
            <article class="fe-card">
                <a href="{{ route($detailRoute, $item->slug) }}" class="block aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-white/5">
                    <img loading="lazy" src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" alt="{{ $item->judul }}"
                        class="h-full w-full object-cover transition duration-500 hover:scale-105">
                </a>
                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                        <span>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y') }}</span>
                        <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span class="font-semibold text-brand-700 dark:text-brand-400">
                            {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                        </span>
                        <span class="ml-auto inline-flex items-center gap-1"><i class="fa-regular fa-eye"></i>{{ number_format($item->views) }}</span>
                    </div>
                    <a href="{{ route($detailRoute, $item->slug) }}" title="{{ $item->judul }}"
                        class="mt-3 line-clamp-2 text-base font-bold text-navy-800 transition hover:text-brand-700 dark:text-white dark:hover:text-brand-400">
                        {{ Str::limit($item->judul, 60) }}
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    @if ($items->hasPages())
        {{ $items->links('components.fe.pagination') }}
    @endif
@else
    <x-fe.empty-state icon="fa-inbox" title="Tidak ada data" text="Mohon maaf, data yang Bapak/Ibu cari belum tersedia." />
@endif
