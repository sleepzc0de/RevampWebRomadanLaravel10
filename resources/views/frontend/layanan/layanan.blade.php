@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Layanan — Biro Manajemen BMN dan Pengadaan')

@section('content')
@php
    $item = $layanan->first();
    $galleryItems = $item
        ? \App\Helpers\MediaHelper::galleryItems(
            $item->image,
            $item->additionalImages ?? [],
            $item->video_url,
            'image_path',
            $item->judul ?? 'Layanan'
        )
        : [];
@endphp

<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Kami Melayani</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Layanan</h1>
        </div>

        @if ($item)
            <div class="mt-12 grid gap-10 lg:grid-cols-2 lg:items-start lg:gap-14">
                <div class="order-2 lg:order-1">
                    <x-fe.media-gallery :items="$galleryItems" />
                </div>
                <div class="prose-fe order-1 lg:order-2">
                    {!! clean($item->layanan) !!}
                </div>
            </div>
        @else
            <x-fe.empty-state class="mt-12" icon="fa-handshake" title="Belum ada data" text="Data layanan belum tersedia, silakan hubungi administrator." />
        @endif
    </div>
</section>
@endsection
