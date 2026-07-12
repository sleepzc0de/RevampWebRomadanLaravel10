@php $about = $tentang->first(); @endphp

@if ($about)
    <section class="bg-white py-20 sm:py-24 dark:bg-navy-950">
        <div class="fe-container grid items-center gap-12 lg:grid-cols-2">
            <div class="relative order-2 lg:order-1">
                <div class="aspect-[4/3] overflow-hidden rounded-3xl shadow-[var(--shadow-lift)]">
                    <img src="{{ asset('storage/romadan_gambar_web/' . $about->image) }}" alt="{{ $about->judul }}"
                        class="h-full w-full object-cover" loading="lazy">
                </div>
                <div class="absolute -bottom-6 -left-6 hidden rounded-2xl bg-brand-700 px-6 py-5 text-white shadow-[var(--shadow-lift)] sm:block dark:bg-brand-600">
                    <div class="text-2xl font-extrabold">BMN</div>
                    <div class="text-xs font-medium text-brand-100">&amp; Pengadaan</div>
                </div>
            </div>

            <div class="order-1 lg:order-2">
                <span class="fe-eyebrow">Tentang Kami</span>
                <h2 class="mt-4 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">{{ $about->judul }}</h2>
                <p class="mt-5 text-[15px] leading-relaxed text-slate-600 sm:text-base dark:text-slate-400">
                    {{ Str::limit(strip_tags($about->excerpt), 220) }}
                </p>
                <a href="{{ route('tentang-fe') }}" class="fe-btn fe-btn-primary mt-2">
                    Baca Profil Kami <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>
@endif
