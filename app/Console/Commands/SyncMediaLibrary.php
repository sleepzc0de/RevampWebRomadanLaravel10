<?php

namespace App\Console\Commands;

use App\Models\backend\MenuMedia\MediaModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Backfill tabel media dari file yang sudah ada di storage tapi belum
 * tercatat — supaya Media Library langsung berisi seluruh unggahan lama,
 * bukan cuma yang baru diunggah setelah fitur ini dibuat.
 */
class SyncMediaLibrary extends Command
{
    protected $signature = 'media:sync';

    protected $description = 'Sinkronkan tabel media dengan file yang sudah ada di storage/app/public';

    private const FOLDERS = ['romadan_gambar_web', 'romadan_file_web', 'photos'];

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $created = 0;
        $skipped = 0;

        foreach (self::FOLDERS as $folder) {
            if (! $disk->exists($folder)) {
                continue;
            }

            foreach ($disk->files($folder) as $path) {
                $filename = basename($path);

                if (MediaModel::where('folder', $folder)->where('filename', $filename)->exists()) {
                    $skipped++;

                    continue;
                }

                MediaModel::create([
                    'disk' => 'public',
                    'folder' => $folder,
                    'filename' => $filename,
                    'original_name' => null,
                    'mime_type' => $disk->mimeType($path) ?: null,
                    'size' => $disk->size($path),
                    'uploaded_by' => null,
                ]);

                $created++;
            }
        }

        $this->info("Media library disinkronkan: {$created} file baru dicatat, {$skipped} sudah ada sebelumnya.");

        return self::SUCCESS;
    }
}
