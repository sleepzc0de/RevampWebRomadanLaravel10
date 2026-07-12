{{-- Data terstruktur Article/schema.org untuk halaman detail Berita/Warta/
     Artikel. Membantu Google menampilkan rich snippet (tanggal, penulis,
     gambar) di hasil pencarian. Menerima: $data (PublikasiModel). --}}
@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $data->judul,
    'description' => Str::limit(strip_tags($data->isi), 155),
    'image' => $data->image ? [asset('storage/romadan_gambar_web/' . $data->image)] : [],
    'datePublished' => optional($data->created_at)->toAtomString(),
    'dateModified' => optional($data->updated_at)->toAtomString(),
    'author' => [
        '@type' => 'Organization',
        'name' => $data->penulis ?: 'Biro Manajemen BMN dan Pengadaan',
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Biro Manajemen BMN dan Pengadaan',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('frontend_romadan_web/images/icons/romadan/logo_3.png'),
        ],
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url()->current(),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
