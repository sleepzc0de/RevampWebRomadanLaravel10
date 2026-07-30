<?php

/*
|--------------------------------------------------------------------------
| Token segmen URL backend (CMS)
|--------------------------------------------------------------------------
|
| Seluruh segmen URL di bawah /backend memakai token acak ini, BUKAN nama
| modul aslinya, supaya struktur URL CMS tidak bisa ditebak/di-enumerasi
| dari luar. Nama route TIDAK berubah sama sekali — semua view/controller
| tetap memanggil route('publikasi.index') dst. seperti biasa, jadi
| mengganti token di sini cukup satu tempat dan aman kapan saja.
|
| Catatan: ini lapisan obscurity tambahan; proteksi utama tetap middleware
| auth + role pada setiap route.
|
*/

return [
    'interface' => 'q7Kp2xM9rT',
    'dashboard' => 'dV3nW8sZ1c',

    'tim' => 'tR5jL0aQ6e',
    'pengembang' => 'pG9dF4hN2u',
    'tim_konten' => 'zK4mR8vD6t',
    'users' => 'uB6mC1vX7k',

    'layanan_group' => 'lY4tE8oI3w',
    'layanan' => 'lS2rA7pD9f',

    'infopub_group' => 'iH8gJ3kU5n',
    'infopub' => 'iZ1qO6bV4x',
    'infopub_home_index' => 'hM7cP2eR8t',
    'infopub_home_create' => 'hK3vT9wA5y',
    'infopub_home_edit' => 'hF6xS1zG7j',
    'infopub_home_delete' => 'hQ4bN8dL2m',
    'peraturan' => 'rW9uH5yC3o',
    'aplikasi' => 'aE2kM7fJ0p',
    'pedoman' => 'mD5oG1sB8q',

    'medsos' => 'sT8lV4nZ6r',
    'loggambar' => 'gC1wY7iK9a',

    'profile_group' => 'fN4hX0uE5b',
    'tentang' => 'nJ7aQ3tS1v',
    'visimisi' => 'vP0eL6cW8d',
    'sejarah' => 'jU3fB9gM4h',
    'struktur' => 'kA6iD2xT7g',
    'jabatan' => 'bO9sK5jF3l',
    'pejabat' => 'pI2gR8hY6z',

    'faq_group' => 'qX5mA1oN7c',
    'faq' => 'qL8pE4uJ2s',

    'referensi_group' => 'eG1cV7yQ9i',
    'kategori' => 'eK4nT0aX6u',
    'status' => 'eS7zH3dO1w',
    'tipe' => 'eF0jW6mB8e',
    'jenis_peraturan' => 'eR3vM9kP5t',
    'status_peraturan' => 'eY6qA2fL0n',

    'publikasi' => 'bJ9wU5rG1x',
    'pub_export' => 'bC2eZ8sV4o',
    'pub_revisions' => 'bT5hK1nD7a',
    'pub_revision_restore' => 'bM8xF4cI0y',
    'pub_sampah' => 'wQ1dP7vN3j',
    'pub_restore' => 'wS4gB0zK6e',
    'pub_force_delete' => 'wV7kE3aH9r',
    'pub_restore_all' => 'wY0nJ6tC2u',

    'kegiatan' => 'cO3rL9wS5g',

    'backups' => 'xF6uQ2mY8b',
    'backups_cleanup' => 'xI9zT5eA1d',
    'backups_download' => 'xL2cW8hV4f',

    'activity_log' => 'xN5fD1jO7h',
    'activity_export' => 'xP8iG4vR0k',
    'activity_clean' => 'xR1lJ7yU3m',

    'contact_info' => 'xT4oM0bX6p',
    'footer_link' => 'xW7rP3eZ9s',

    'visitors' => 'xZ0uS6hC2v',
    'visitors_export' => 'yB3xV9kF5w',
    'visitors_clean' => 'yD6aY2nI8z',

    'media' => 'yG9dB5qL1c',
    'media_sync' => 'yJ2gE8tO4e',
    'media_blob' => 'yM5jH1wR7g',

    'security' => 'yO8mK4zU0i',
    'twofa' => 'yR1pN7cX3k',
    'twofa_enable' => 'yT4sQ0fA6m',
    'twofa_cancel' => 'yV7vT3iD9o',
    'twofa_confirm' => 'yY0yW6lG2q',
    'twofa_regen' => 'zA3bZ9oJ5s',

    // Endpoint upload gambar dari CKEditor (lihat MediaController@ckeditorUpload)
    'ck_upload' => 'zC6eD2rM8u',

    'system_monitor' => 'zF7hL1qN4y',
    'system_monitor_data' => 'zH0jP6sB2w',

    // Kata kerja resource Laravel (…/create, …/{id}/edit) — ikut ditokenkan
    // lewat Route::resourceVerbs() di RouteServiceProvider.
    'verb_create' => 'nQ2fV8xB4d',
    'verb_edit' => 'eW5iA1kP7f',
];
