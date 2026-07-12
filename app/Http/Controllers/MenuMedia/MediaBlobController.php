<?php

namespace App\Http\Controllers\MenuMedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streaming gambar khusus untuk dalam CMS backend (thumbnail listing,
 * preview form edit, Media Library) supaya tidak diakses langsung lewat
 * URL publik yang bisa ditebak — hanya bisa diambil lewat request
 * ter-otentikasi (fetch dari halaman backend yang sudah login), lalu
 * ditampilkan browser sebagai blob URL. Gambar publik di frontend TIDAK
 * memakai jalur ini (tetap URL storage biasa, demi SEO & performa).
 */
class MediaBlobController extends Controller
{
    private const ALLOWED_FOLDERS = ['romadan_gambar_web', 'romadan_file_web', 'photos'];

    public function show(Request $request, string $folder, string $filename): StreamedResponse
    {
        abort_unless(in_array($folder, self::ALLOWED_FOLDERS, true), 404);

        // Cegah path traversal — nama file tidak boleh mengandung pemisah direktori.
        abort_if(str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..'), 404);

        $path = $folder.'/'.$filename;
        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        return $disk->response($path, $filename, [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
