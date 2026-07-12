@include('frontend.publikasi.partials._list-tipe', [
    'items' => $artikel,
    'isSearch' => $isSearch,
    'searchValue' => $searchValue,
    'detailRoute' => 'artikel-fe',
])
