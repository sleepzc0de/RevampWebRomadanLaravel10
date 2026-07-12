@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Struktur Organisasi — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Profil</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Struktur Organisasi</h1>
        </div>

        @if (session('error'))
            <div class="mx-auto mt-8 max-w-2xl rounded-xl border border-red-200 bg-red-50 p-4 text-center text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        @forelse ($organisasi as $item)
            @php
                $galleryItems = \App\Helpers\MediaHelper::galleryItems(
                    $item->image,
                    $item->additionalImages->sortBy('sort_order'),
                    $item->video_url,
                    'image_path',
                    $item->judul ?? 'Struktur Organisasi'
                );
            @endphp

            <div class="mt-14 grid gap-10 first:mt-12 lg:items-start lg:gap-14 {{ $loop->even ? 'lg:grid-cols-2' : 'lg:grid-cols-2' }}">
                <div class="{{ $loop->even ? 'lg:order-2' : 'lg:order-1' }} order-2">
                    <x-fe.media-gallery :items="$galleryItems" />
                </div>
                <div class="prose-fe {{ $loop->even ? 'lg:order-1' : 'lg:order-2' }} order-1 flex flex-col justify-center">
                    {!! clean($item->struktur) !!}
                </div>
            </div>
        @empty
            <x-fe.empty-state class="mt-12" icon="fa-sitemap" title="Belum ada data" text="Data struktur organisasi belum tersedia, silakan hubungi administrator." />
        @endforelse
    </div>
</section>
@endsection
