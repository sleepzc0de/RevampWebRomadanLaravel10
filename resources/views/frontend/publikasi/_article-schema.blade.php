{{-- Data terstruktur Article/schema.org untuk halaman detail Berita/Warta/
     Artikel. Membantu Google menampilkan rich snippet (tanggal, penulis,
     gambar) di hasil pencarian. Menerima: $data (PublikasiModel). --}}
@push('structured-data')
<script type="application/ld+json">
{{-- JANGAN tulis '@context' sebagai satu literal: Laravel 13 punya directive
     Blade bernama @context, sehingga token itu ikut dikompilasi menjadi kode
     PHP di tengah JSON (structured data rusak + source PHP bocor ke publik).
     Memecahnya jadi '@'.'context' membuat Blade tidak mengenalinya. --}}
{!! json_encode([
    '@'.'context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $data->judul,
    'description' => Str::limit(strip_tags($data->isi), 155),
    'image' => $data->image ? [asset('storage/romadan_gambar_web/' . $data->image)] : [],
    'datePublished' => optional($data->tanggal_terbit)->toAtomString(),
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
{{-- JSON_HEX_* wajib: tanpa itu nilai yang mengandung "</script>" akan
     menutup blok script lebih awal dan sisanya dieksekusi sebagai HTML
     (XSS tersimpan). Karakter < > & ' " dikodekan jadi \u00XX — tetap
     JSON yang sah dan tetap terbaca Google. --}}
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
@endpush
