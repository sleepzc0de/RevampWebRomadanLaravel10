{{-- Partial bersama untuk halaman detail publikasi (berita/warta/artikel).
     Menerima: $data, $tb, $backRoute, $tipeLabel --}}

@php
    $galleryItems = $data->images->count() > 0
        ? $data->images->map(fn ($img) => ['type' => 'image', 'src' => asset('storage/romadan_gambar_web/' . $img->image_path), 'alt' => $data->judul])->all()
        : ($data->image ? [['type' => 'image', 'src' => asset('storage/romadan_gambar_web/' . $data->image), 'alt' => $data->judul]] : []);

    $embedUrl = \App\Helpers\MediaHelper::toEmbedUrl($data->embedded_media);
    $isEmbedVideo = $embedUrl && (str_contains($data->embedded_media, 'youtube.com') || str_contains($data->embedded_media, 'youtu.be') || str_contains($data->embedded_media, 'vimeo.com'));
    if ($isEmbedVideo) {
        $galleryItems[] = ['type' => 'video', 'embedUrl' => $embedUrl];
    }
@endphp

<article class="bg-white py-16 sm:py-20 dark:bg-navy-950">
    <div class="fe-container">
        <a href="{{ route($backRoute) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke {{ $tipeLabel }}
        </a>

        <div class="mt-6 flex items-center justify-center gap-3 text-xs font-medium text-slate-500 dark:text-slate-400">
            <span>{{ $tb }}</span>
            <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
            <span class="inline-flex items-center gap-1.5"><i class="fa-regular fa-eye"></i>{{ number_format($data->views ?? 0) }} Views</span>
        </div>

        <h1 class="mt-3 text-center text-2xl font-extrabold text-navy-800 sm:text-3xl dark:text-white">{{ $data->judul }}</h1>

        @if (count($galleryItems))
            <div class="mt-8">
                <x-fe.media-gallery :items="$galleryItems" ratio="aspect-[16/9]" />
            </div>
        @endif

        @if (!$isEmbedVideo && $data->embedded_media)
            <div class="prose-fe mt-6">{!! $data->getEmbeddedMediaHtml() !!}</div>
        @endif

        <div class="prose-fe mt-8">
            {!! clean($data->isi) !!}
        </div>
    </div>
</article>
