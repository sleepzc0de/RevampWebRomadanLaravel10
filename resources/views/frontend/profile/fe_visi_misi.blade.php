@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Visi & Misi — Biro Manajemen BMN dan Pengadaan')

@section('content')
@php
    $item = $visimisi->first();
    $galleryItems = $item
        ? \App\Helpers\MediaHelper::galleryItems($item->image, $item->images->sortBy('sort_order'), $item->video_url, 'image', $item->judul ?? 'Visi Misi')
        : [];
@endphp

<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Profil</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Visi &amp; Misi</h1>
        </div>

        @if ($item)
            <div class="mt-12 grid gap-10 lg:grid-cols-2 lg:items-start lg:gap-14">
                <div class="order-2 lg:order-1">
                    <x-fe.media-gallery :items="$galleryItems" />
                </div>
                <div class="order-1 space-y-8 lg:order-2">
                    <div>
                        <h2 class="text-xl font-bold text-brand-700 dark:text-brand-400">Visi</h2>
                        <div class="prose-fe mt-3">{!! clean($item->visi) !!}</div>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-brand-700 dark:text-brand-400">Misi</h2>
                        <div class="prose-fe mt-3">{!! clean($item->misi) !!}</div>
                    </div>
                </div>
            </div>
        @else
            <x-fe.empty-state class="mt-12" icon="fa-bullseye" title="Belum ada data" text="Data Visi & Misi belum tersedia, silakan hubungi administrator." />
        @endif
    </div>
</section>
@endsection
