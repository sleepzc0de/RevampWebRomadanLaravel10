<footer class="bg-navy-900 text-slate-300">
    <div class="fe-container grid gap-10 py-14 lg:grid-cols-12">
        {{-- Brand --}}
        <div class="lg:col-span-5">
            <a href="{{ route('homefe') }}" class="flex items-center gap-3">
                <img src="{{ asset('frontend_romadan_web/images/icons/romadan/logo_3.png') }}" alt="Logo" class="h-11 w-11 object-contain">
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-white">Biro Manajemen BMN &amp; Pengadaan</span>
                    <span class="block text-xs text-slate-400">Kementerian Keuangan Republik Indonesia</span>
                </span>
            </a>
            <p class="mt-5 max-w-sm text-sm leading-relaxed text-slate-400">
                Mengelola Barang Milik Negara dan proses pengadaan secara transparan, akuntabel, dan berorientasi pada pelayanan publik yang prima.
            </p>

            <div class="mt-6 flex flex-wrap gap-2">
                @forelse ($medsos ?? [] as $item)
                    <a href="{{ $item->link_medsos }}" target="_blank" rel="noopener noreferrer"
                       title="{{ $item->nama_medsos }}"
                       class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:-translate-y-0.5 hover:bg-gold-500 hover:text-navy-900">
                        <i class="{{ $item->logo_medsos }}"></i>
                    </a>
                @empty
                    <span class="text-xs text-slate-500">Media sosial belum tersedia.</span>
                @endforelse
            </div>
        </div>

        {{-- Tautan --}}
        <div class="lg:col-span-3">
            <h3 class="text-xs font-bold tracking-wide text-white uppercase">Tautan</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                @forelse ($footerLinks ?? [] as $item)
                    <li><a href="{{ $item->url }}" class="text-slate-400 transition hover:text-gold-400">{{ $item->label }}</a></li>
                @empty
                    <li><a href="{{ route('layanan-fe') }}" class="text-slate-400 transition hover:text-gold-400">Layanan</a></li>
                    <li><a href="{{ route('informasi-publik-index-fe') }}" class="text-slate-400 transition hover:text-gold-400">Informasi Publik</a></li>
                    <li><a href="{{ route('publikasi-index-fe') }}" class="text-slate-400 transition hover:text-gold-400">Publikasi</a></li>
                    <li><a href="{{ route('faq-index-fe') }}" class="text-slate-400 transition hover:text-gold-400">FAQ</a></li>
                    <li><a href="{{ route('tentang-fe') }}" class="text-slate-400 transition hover:text-gold-400">Tentang Kami</a></li>
                @endforelse
            </ul>
        </div>

        {{-- Kontak --}}
        <div class="lg:col-span-4">
            <h3 class="text-xs font-bold tracking-wide text-white uppercase">Hubungi Kami</h3>
            <ul class="mt-4 space-y-3 text-sm text-slate-400">
                <li class="flex items-start gap-3">
                    <i class="fa-regular fa-envelope mt-0.5 text-gold-500"></i>
                    <a href="mailto:{{ $contactInfo->email ?? 'kemenkeu.prime@kemenkeu.go.id' }}" class="transition hover:text-gold-400">{{ $contactInfo->email ?? 'kemenkeu.prime@kemenkeu.go.id' }}</a>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fa-brands fa-whatsapp mt-0.5 text-gold-500"></i>
                    <span>{{ $contactInfo->whatsapp ?? '0813-1000-4134' }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fa-regular fa-building mt-0.5 text-gold-500"></i>
                    <span>{{ $contactInfo->address ?? 'Gedung Djuanda 2 Lt. 16–17, Jl. Dr. Wahidin Raya No. 1' }}</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="fe-container flex flex-col items-center justify-between gap-2 py-5 text-xs text-slate-500 sm:flex-row">
            <span>&copy; {{ date('Y') }} Biro Manajemen BMN dan Pengadaan &mdash; Kementerian Keuangan RI.</span>
            <span>Seluruh hak cipta dilindungi undang-undang.</span>
        </div>
    </div>
</footer>
