<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // HSTS HANYA boleh dikirim pada koneksi HTTPS.
        //
        // Mengirimnya lewat HTTP tidak ada manfaatnya (RFC 6797 menyuruh
        // browser mengabaikan HSTS yang diterima via transport tak aman),
        // tetapi berbahaya dalam praktik: browser menyimpan kebijakan untuk
        // host tersebut lalu memaksa SEMUA permintaan ke host itu memakai
        // HTTPS. Pada pengembangan lokal (`php artisan serve` hanya melayani
        // HTTP) akibatnya seluruh CSS/JS/gambar gagal dimuat dan server
        // mencatat "Invalid request (Unsupported SSL request)" — halaman
        // tampil tanpa gaya sama sekali. Karena memakai includeSubDomains,
        // dampaknya bahkan meluas ke semua proyek lain di localhost.
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
