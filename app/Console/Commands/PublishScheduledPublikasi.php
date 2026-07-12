<?php

namespace App\Console\Commands;

use App\Models\backend\MenuPublikasi\PublikasiModel;
use Illuminate\Console\Command;

/**
 * Publikasikan otomatis publikasi berstatus "scheduled" yang sudah
 * melewati waktu published_at. Update dilakukan satu per satu (bukan
 * bulk query) supaya trait LogsActivity tetap mencatat perubahan status
 * di Activity Log seperti publish manual.
 */
class PublishScheduledPublikasi extends Command
{
    protected $signature = 'publikasi:publish-scheduled';

    protected $description = 'Publikasikan otomatis publikasi terjadwal yang sudah waktunya tayang';

    public function handle(): int
    {
        $due = PublikasiModel::where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get();

        foreach ($due as $item) {
            $item->update(['status' => 'published']);
        }

        if ($due->isNotEmpty()) {
            $this->info("{$due->count()} publikasi terjadwal berhasil dipublikasikan.");
        }

        return self::SUCCESS;
    }
}
