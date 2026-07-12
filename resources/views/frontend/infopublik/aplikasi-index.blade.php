@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Portal Aplikasi — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Informasi Publik</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Portal Aplikasi</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">Kumpulan aplikasi &amp; layanan digital yang tersedia.</p>
        </div>

        <div x-data="liveSearch({
                url: @js(route('informasi-publik-aplikasi-index-fe')),
                param: 'cari_aplikasi',
                json: true,
                responseType: 'json',
                target: '#applicationsGrid',
                paginationTarget: '#paginationContainer',
                initialValue: @js(request('cari_aplikasi', '')),
            })" class="mt-10">
            <div class="relative mx-auto max-w-xl">
                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="q" @keydown.enter.prevent="submitNow" placeholder="Cari aplikasi..."
                    class="w-full rounded-full border border-slate-200 py-3 pr-32 pl-11 text-sm text-slate-700 shadow-[var(--shadow-soft)] transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100 focus:outline-none dark:border-white/10 dark:bg-navy-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:ring-brand-500/20">
                <div class="absolute top-1/2 right-2 flex -translate-y-1/2 gap-1.5">
                    <button type="button" @click="submitNow" class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-700 text-white transition hover:bg-brand-800">
                        <i class="fa-solid fa-magnifying-glass text-xs" x-show="!loading"></i>
                        <i class="fa-solid fa-spinner animate-spin text-xs" x-show="loading" x-cloak></i>
                    </button>
                    <button type="button" @click="refresh" title="Muat ulang" class="flex h-9 w-9 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition hover:bg-gold-600">
                        <i class="fa-solid fa-rotate-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div id="applicationsGrid" class="mt-10 grid gap-5 transition-opacity sm:grid-cols-2 lg:grid-cols-3" :class="loading ? 'opacity-50' : ''">
                @include('frontend.infopublik.partials.applications-grid')
            </div>

            <div id="paginationContainer" @click="onPagerClick">
                {{ $data->links('components.fe.pagination') }}
            </div>
        </div>
    </div>
</section>
@endsection
