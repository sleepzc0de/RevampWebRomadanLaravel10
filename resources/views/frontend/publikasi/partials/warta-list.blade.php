@include('frontend.publikasi.partials._list-tipe', [
    'items' => $warta,
    'isSearch' => $isSearch,
    'searchValue' => $searchValue,
    'detailRoute' => 'warta-fe',
])
