@php
    $kumpulanPeraturan = [
        [
            'label' => 'BMN',
            'icon' => 'fa-building-columns',
            'desc' => 'Kumpulan peraturan tentang Perencanaan BMN, Pengelolaan BMN, Penatausahaan BMN, dan lain-lain.',
        ],
        [
            'label' => 'Pengadaan',
            'icon' => 'fa-cart-shopping',
            'desc' => 'Kumpulan peraturan tentang Pengadaan Barang dan Jasa, dan lain-lain.',
        ],
    ];
@endphp

<section class="bg-white py-20 sm:py-24 dark:bg-navy-950">
    <div class="fe-container">
        <div class="flex flex-col items-center gap-2 text-center">
            <span class="fe-eyebrow">Regulasi</span>
            <h2 class="max-w-2xl text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Kumpulan Peraturan Tentang BMN &amp; Pengadaan</h2>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2">
            @foreach ($kumpulanPeraturan as $item)
                <a href="{{ route('informasi-publik-peraturan-index-fe') }}"
                   class="group relative flex flex-col overflow-hidden rounded-3xl bg-navy-900 p-8 text-white shadow-[var(--shadow-soft)] transition duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-lift)] sm:p-10">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gold-500 text-navy-900">
                        <i class="fa-solid {{ $item['icon'] }} text-xl"></i>
                    </span>
                    <h3 class="mt-6 text-2xl font-bold">{{ $item['label'] }}</h3>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-slate-300">{{ $item['desc'] }}</p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-gold-400">
                        Lihat Semuanya
                        <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
