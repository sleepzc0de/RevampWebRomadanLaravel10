<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nama penerbit (issuer) autentikasi dua faktor
    |--------------------------------------------------------------------------
    |
    | Nama inilah yang muncul di aplikasi authenticator pengguna (Google
    | Authenticator, Authy, Microsoft Authenticator, dsb.) sebagai label
    | akun — ditampilkan bersama alamat email pemilik akun.
    |
    | Sengaja dipisahkan dari APP_NAME: APP_NAME juga dipakai untuk hal lain
    | (mis. nama pengirim email), sedangkan nama issuer 2FA sebaiknya stabil.
    |
    | CATATAN PENTING: mengubah nilai ini TIDAK mengubah entri yang sudah
    | terdaftar di ponsel pengguna. Entri lama tetap memakai nama lama dan
    | kodenya tetap berfungsi; nama baru hanya berlaku untuk pendaftaran
    | 2FA berikutnya.
    |
    */

    'issuer' => env('TWO_FACTOR_ISSUER', 'CMS Romadan Kemenkeu'),

];
