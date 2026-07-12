<?php

namespace App\Helpers;

class MediaHelper
{
    /**
     * Ubah URL YouTube/Vimeo biasa menjadi URL embed untuk iframe.
     * Dipakai galeri media di halaman profil, layanan, dan detail publikasi.
     */
    public static function toEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (str_contains($url, 'youtube.com/watch')) {
            $videoId = substr($url, strpos($url, 'v=') + 2);
            if (str_contains($videoId, '&')) {
                $videoId = substr($videoId, 0, strpos($videoId, '&'));
            }

            return "https://www.youtube.com/embed/{$videoId}?autoplay=0";
        }

        if (str_contains($url, 'youtu.be/')) {
            $videoId = substr($url, strrpos($url, '/') + 1);

            return "https://www.youtube.com/embed/{$videoId}?autoplay=0";
        }

        if (str_contains($url, 'vimeo.com/')) {
            $vimeoId = substr($url, strrpos($url, '/') + 1);

            return "https://player.vimeo.com/video/{$vimeoId}";
        }

        return $url;
    }

    /**
     * Bangun array item galeri (gambar + opsional video) siap-pakai untuk
     * komponen <x-fe.media-gallery>, dari kombinasi gambar utama, koleksi
     * gambar tambahan, dan URL video.
     *
     * @param  iterable<int, array<string, mixed>|object>  $additional  koleksi/array dengan properti atau key $imageField
     */
    public static function galleryItems(
        ?string $mainImage,
        iterable $additional = [],
        ?string $videoUrl = null,
        string $imageField = 'image_path',
        string $alt = ''
    ): array {
        $items = [];

        if (! empty($mainImage)) {
            $items[] = ['type' => 'image', 'src' => asset('storage/romadan_gambar_web/'.$mainImage), 'alt' => $alt];
        }

        foreach ($additional as $img) {
            $path = is_array($img) ? ($img[$imageField] ?? null) : ($img->{$imageField} ?? null);
            if ($path) {
                $items[] = ['type' => 'image', 'src' => asset('storage/romadan_gambar_web/'.$path), 'alt' => $alt];
            }
        }

        if ($embedUrl = self::toEmbedUrl($videoUrl)) {
            $items[] = ['type' => 'video', 'embedUrl' => $embedUrl];
        }

        return $items;
    }
}
