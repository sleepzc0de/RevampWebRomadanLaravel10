@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Sejarah — Biro Manajemen BMN dan Pengadaan')

@section('content')
@php
    $item = $sejarah->first();
    $galleryItems = [];
    if ($item) {
        $mediaItems = !empty($item->media) ? (json_decode($item->media, true) ?? []) : [];
        if (empty($mediaItems) && !empty($item->image)) {
            $mediaItems = [['type' => 'image', 'path' => $item->image]];
        }
        foreach ($mediaItems as $m) {
            if (($m['type'] ?? null) === 'image' && !empty($m['path'])) {
                $galleryItems[] = ['type' => 'image', 'src' => asset('storage/romadan_gambar_web/' . $m['path']), 'alt' => 'Sejarah'];
            } elseif (($m['type'] ?? null) === 'video' && !empty($m['url'])) {
                $galleryItems[] = ['type' => 'video', 'embedUrl' => \App\Helpers\MediaHelper::toEmbedUrl($m['url'])];
            }
        }
    }
@endphp

<section class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <div class="text-center">
            <span class="fe-eyebrow justify-center">Profil</span>
            <h1 class="mt-3 text-3xl font-extrabold text-navy-800 sm:text-4xl dark:text-white">Sejarah</h1>
        </div>

        @if ($item)
            <div class="mt-12 grid gap-10 lg:grid-cols-2 lg:items-start lg:gap-14">
                <div class="order-2 lg:order-1">
                    <x-fe.media-gallery :items="$galleryItems" />
                </div>
                <div class="prose-fe order-1 lg:order-2">
                    {!! clean($item->sejarah) !!}
                </div>
            </div>
        @else
            <x-fe.empty-state class="mt-12" icon="fa-landmark" title="Belum ada data" text="Data sejarah belum tersedia, silakan hubungi administrator." />
        @endif
    </div>
</section>
@endsection
