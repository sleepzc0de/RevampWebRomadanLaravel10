@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Informasi Publik — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Transparansi</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">{{ $info_publik->judul ?? 'Informasi Publik' }}</h1>
        </div>

        @if (!empty($info_publik?->isi))
            <div class="prose-fe mx-auto mt-8 max-w-3xl">
                {!! clean($info_publik->isi) !!}
            </div>
        @endif

        @forelse ($infolist as $item)
            @if ($loop->first)
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @endif

            <a href="{{ route($item->link_list_informasi) }}" class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-[var(--shadow-soft)] transition duration-300 hover:-translate-y-1 hover:shadow-[var(--shadow-lift)] dark:border-white/10 dark:bg-navy-900">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                    <i class="fa-solid fa-folder-open"></i>
                </span>
                <h3 class="mt-4 text-lg font-bold text-navy-800 dark:text-white">{{ Str::limit($item->judul_list_informasi, 20) }}</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    {{ Str::limit(strip_tags($item->isi_list_informasi), 90) }}
                </p>
                <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-700 dark:text-brand-400">
                    Lihat Selengkapnya
                    <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                </span>
            </a>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <x-fe.empty-state class="mt-12" icon="fa-folder-open" title="Belum ada data" text="Informasi publik belum tersedia, silakan hubungi administrator." />
        @endforelse
    </div>
</section>
@endsection
