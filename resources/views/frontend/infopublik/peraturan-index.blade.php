@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Peraturan — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-slate-50 py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div>
            <span class="fe-eyebrow">Informasi Publik</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Peraturan</h1>
            <p class="mt-2 max-w-xl text-slate-500 dark:text-slate-400">Akses peraturan dan regulasi terkait Barang Milik Negara &amp; Pengadaan.</p>
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-12">
            {{-- Filter --}}
            <aside class="lg:col-span-4">
                <form action="{{ route('informasi-publik-peraturan-index-fe') }}" method="POST" autocomplete="off"
                    class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-[var(--shadow-soft)] dark:border-white/10 dark:bg-navy-900">
                    @csrf
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-4 dark:border-white/10">
                        <i class="fa-solid fa-sliders text-brand-700 dark:text-brand-400"></i>
                        <h2 class="text-sm font-bold tracking-wide text-navy-800 uppercase dark:text-white">Filter Pencarian</h2>
                    </div>

                    <div class="relative mt-4">
                        <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="cari_peraturan" placeholder="Cari peraturan..."
                            value="{{ request('cari_peraturan') ?? '' }}"
                            class="w-full rounded-xl border border-slate-200 py-2.5 pr-4 pl-10 text-sm text-slate-700 transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100 focus:outline-none dark:border-white/10 dark:bg-navy-800 dark:text-white dark:placeholder:text-slate-500 dark:focus:ring-brand-500/20">
                    </div>

                    <div class="mt-6">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-navy-800 dark:text-white">
                            <i class="fa-solid fa-tag text-xs text-brand-700 dark:text-brand-400"></i> Kategori
                        </h3>
                        <div class="mt-3 space-y-2">
                            @forelse ($kategori as $item)
                                <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm text-slate-600 transition hover:bg-brand-50 dark:text-slate-300 dark:hover:bg-brand-500/10">
                                    <input type="checkbox" name="kategori[]" value="{{ $item->nama_kategori }}"
                                        class="h-4 w-4 rounded accent-brand-700"
                                        {{ is_array($selectedKategori) && in_array($item->nama_kategori, $selectedKategori) ? 'checked' : '' }}>
                                    {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                                </label>
                            @empty
                                <p class="text-xs text-slate-400">Tidak ada kategori.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-5 dark:border-white/10">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-navy-800 dark:text-white">
                            <i class="fa-solid fa-file-lines text-xs text-brand-700 dark:text-brand-400"></i> Jenis Peraturan
                        </h3>
                        <div class="mt-3 space-y-2">
                            @forelse ($jenis_peraturan as $item)
                                <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm text-slate-600 transition hover:bg-brand-50 dark:text-slate-300 dark:hover:bg-brand-500/10">
                                    <input type="checkbox" name="jenis_peraturan[]" value="{{ $item->nama_jenis_peraturan }}"
                                        class="h-4 w-4 rounded accent-brand-700"
                                        {{ is_array($selectedJenisPeraturan) && in_array($item->nama_jenis_peraturan, $selectedJenisPeraturan) ? 'checked' : '' }}>
                                    {{ Str::limit($item->nama_jenis_peraturan, 30) }}
                                </label>
                            @empty
                                <p class="text-xs text-slate-400">Tidak ada jenis peraturan.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <button type="submit" class="fe-btn fe-btn-primary flex-1 py-2.5">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari
                        </button>
                        <a href="{{ route('informasi-publik-peraturan-index-fe') }}" class="fe-btn fe-btn-ghost py-2.5">
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                        </a>
                    </div>
                </form>
            </aside>

            {{-- List --}}
            <div class="lg:col-span-8">
                <div class="mb-5 flex items-center gap-3">
                    <h2 class="text-lg font-bold text-navy-800 dark:text-white">Daftar Peraturan</h2>
                    <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-bold text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">{{ $peraturan->total() }} peraturan</span>
                </div>

                @if ($peraturan->count() > 0)
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($peraturan as $item)
                            <div class="group flex flex-col rounded-2xl border border-slate-200 bg-white shadow-[var(--shadow-soft)] transition duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-lift)] dark:border-white/10 dark:bg-navy-900">
                                <div class="flex-1 p-5">
                                    <h3 class="text-base font-bold text-navy-800 dark:text-white" title="{{ $item->nomor_peraturan }}">
                                        {{ Str::limit($item->nomor_peraturan, 30) }}
                                    </h3>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400" title="{{ $item->judul_peraturan }}">
                                        {{ Str::limit($item->judul_peraturan, 100) }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-5 py-3 dark:border-white/10 dark:bg-white/5">
                                    @if ($item->tanggal_penetapan)
                                        <span class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                            <i class="fa-regular fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal_penetapan)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span></span>
                                    @endif
                                    <a href="{{ route('informasi-publik-peraturan-detail-fe', $item->slug) }}"
                                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300">
                                        Detail <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $peraturan->appends(request()->input())->links('components.fe.pagination') }}
                @else
                    <x-fe.empty-state icon="fa-file-circle-xmark" title="Tidak ada data"
                        text="Mohon maaf, data yang Anda cari tidak ditemukan. Coba gunakan filter atau kata kunci lain." />
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
