@include('errors._error-page', [
    'code' => 419,
    'title' => 'Sesi Kedaluwarsa',
    'message' => 'Halaman ini dibiarkan terbuka terlalu lama sehingga sesi Anda berakhir. Muat ulang lalu coba lagi.',
    'showReload' => true,
])
