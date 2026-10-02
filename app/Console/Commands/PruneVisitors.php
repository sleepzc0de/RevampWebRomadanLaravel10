<?php

namespace App\Console\Commands;

use App\Models\backend\MenuVisitor\VisitorModel;
use Illuminate\Console\Command;

/**
 * Hapus log kunjungan lama. Middleware LogVisitor menyimpan SATU baris per
 * kunjungan halaman (termasuk bot), jadi tanpa pembersihan terjadwal tabel
 * `visitors` tumbuh tanpa batas dan memperlambat dasbor.
 */
class PruneVisitors extends Command
{
    protected $signature = 'visitors:prune {--days=90 : Hapus kunjungan yang lebih lama dari N hari}';

    protected $description = 'Hapus log pengunjung frontend yang sudah melewati masa simpan';

    private const BATCH_SIZE = 5000;

    public function handle(): int
    {
        $days = (int) $this->option('days');

        if ($days < 7) {
            $this->error('--days minimal 7 supaya data analitik terbaru tidak ikut terhapus.');

            return self::INVALID;
        }

        $cutoff = now()->subDays($days);
        $total = 0;

        // Dihapus bertahap agar tidak mengunci tabel lama pada pembersihan pertama
        // yang bisa berisi ratusan ribu baris.
        do {
            $deleted = VisitorModel::where('created_at', '<', $cutoff)->limit(self::BATCH_SIZE)->delete();
            $total += $deleted;
        } while ($deleted === self::BATCH_SIZE);

        $this->info("{$total} baris pengunjung lebih dari {$days} hari dihapus.");

        return self::SUCCESS;
    }
}
