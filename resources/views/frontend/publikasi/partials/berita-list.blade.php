@include('frontend.publikasi.partials._list-tipe', [
    'items' => $berita,
    'isSearch' => $isSearch,
    'searchValue' => $searchValue,
    'detailRoute' => 'berita-fe',
])
