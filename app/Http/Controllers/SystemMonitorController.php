<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SystemMonitorController extends Controller
{
    public function index()
    {
        return view('backend.system-monitor.index', $this->gather());
    }

    /**
     * Endpoint polling ringan (dipanggil Alpine setiap beberapa detik) — hanya
     * mengembalikan angka yang benar-benar berubah antar refresh (memori, disk,
     * koneksi DB), bukan seluruh payload (daftar tabel dsb). Bentuk key sama
     * persis dengan array app/database yang dipakai index(), supaya state
     * Alpine di sisi klien tinggal di-merge tanpa pemetaan nama field.
     */
    public function data()
    {
        $data = $this->gather();

        return response()->json([
            'checked_at' => $data['checked_at'],
            'app' => Arr::only($data['app'], [
                'memory_used_mb', 'memory_peak_mb', 'disk_used_percent',
                'disk_free_gb', 'disk_total_gb', 'log_size_mb',
            ]),
            'database' => Arr::only($data['database'], [
                'connected', 'size_mb', 'active_connections', 'pending_migrations',
            ]),
        ]);
    }

    private function gather(): array
    {
        return [
            'checked_at' => now()->toIso8601String(),
            'app' => $this->applicationMetrics(),
            'database' => $this->databaseMetrics(),
            'storage' => $this->storageChecks(),
        ];
    }

    private function applicationMetrics(): array
    {
        $basePath = base_path();

        $diskTotal = @disk_total_space($basePath) ?: null;
        $diskFree = @disk_free_space($basePath) ?: null;
        $diskUsedPercent = ($diskTotal && $diskFree) ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : null;

        $logPath = storage_path('logs/laravel.log');
        $logSizeMb = file_exists($logPath) ? round(filesize($logPath) / 1048576, 2) : 0;

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => (bool) config('app.debug'),
            'os_family' => PHP_OS_FAMILY,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? php_sapi_name(),
            'memory_used_mb' => round(memory_get_usage(true) / 1048576, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1048576, 2),
            'memory_limit' => ini_get('memory_limit'),
            'disk_total_gb' => $diskTotal ? round($diskTotal / 1073741824, 2) : null,
            'disk_free_gb' => $diskFree ? round($diskFree / 1073741824, 2) : null,
            'disk_used_percent' => $diskUsedPercent,
            'log_size_mb' => $logSizeMb,
            'load_average' => function_exists('sys_getloadavg') ? sys_getloadavg() : null,
            'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes_cached' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'queue_connection' => config('queue.default'),
            'queue_pending' => $this->queuePendingCount(),
        ];
    }

    private function queuePendingCount(): ?int
    {
        if (config('queue.default') !== 'database') {
            return null;
        }

        try {
            return (int) DB::table('jobs')->count();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function databaseMetrics(): array
    {
        $driver = DB::connection()->getDriverName();

        $metrics = [
            'driver' => $driver,
            'database' => DB::connection()->getDatabaseName(),
            'connected' => false,
            'version' => null,
            'size_mb' => null,
            'active_connections' => null,
            'pending_migrations' => null,
            'tables' => [],
            'error' => null,
        ];

        try {
            DB::connection()->getPdo();
            $metrics['connected'] = true;
        } catch (\Throwable $e) {
            $metrics['error'] = 'Tidak dapat terhubung ke database.';
            Log::error('SystemMonitor: koneksi database gagal', ['error' => $e->getMessage()]);

            return $metrics;
        }

        if ($driver === 'sqlsrv') {
            $this->fillSqlServerMetrics($metrics);
        } elseif ($driver === 'mysql') {
            $this->fillMysqlMetrics($metrics);
        }

        $metrics['pending_migrations'] = $this->pendingMigrationsCount();

        return $metrics;
    }

    private function fillSqlServerMetrics(array &$metrics): void
    {
        try {
            $version = DB::selectOne('SELECT @@VERSION AS version')?->version;
            $metrics['version'] = $version ? trim(explode("\n", $version)[0]) : null;
        } catch (\Throwable $e) {
            // Biarkan null — bukan fatal untuk halaman monitor.
        }

        try {
            $size = DB::selectOne('
                SELECT
                    CAST(SUM(CASE WHEN type = 0 THEN size END) * 8.0 / 1024 AS DECIMAL(12,2)) AS data_mb,
                    CAST(SUM(CASE WHEN type = 1 THEN size END) * 8.0 / 1024 AS DECIMAL(12,2)) AS log_mb
                FROM sys.database_files
            ');
            $metrics['size_mb'] = $size ? round(($size->data_mb ?? 0) + ($size->log_mb ?? 0), 2) : null;
        } catch (\Throwable $e) {
            // Butuh akses ke sys.database_files pada database aktif — jika gagal, biarkan null.
        }

        try {
            $conn = DB::selectOne('SELECT COUNT(*) AS cnt FROM sys.dm_exec_sessions WHERE is_user_process = 1');
            $metrics['active_connections'] = $conn?->cnt;
        } catch (\Throwable $e) {
            // Butuh izin VIEW SERVER STATE — kalau user DB tidak punya, biarkan null.
        }

        try {
            $metrics['tables'] = collect(DB::select('
                SELECT
                    t.name AS table_name,
                    SUM(p.rows) AS row_count,
                    CAST(SUM(a.total_pages) * 8.0 / 1024 AS DECIMAL(12,2)) AS size_mb
                FROM sys.tables t
                JOIN sys.indexes i ON t.object_id = i.object_id
                JOIN sys.partitions p ON i.object_id = p.object_id AND i.index_id = p.index_id
                JOIN sys.allocation_units a ON p.partition_id = a.container_id
                WHERE t.is_ms_shipped = 0 AND i.index_id IN (0, 1)
                GROUP BY t.name
                ORDER BY size_mb DESC
            '))->take(10)->map(fn ($row) => [
                'name' => $row->table_name,
                'rows' => (int) $row->row_count,
                'size_mb' => (float) $row->size_mb,
            ])->all();
        } catch (\Throwable $e) {
            // Biarkan kosong — daftar tabel bersifat informatif, bukan kritikal.
        }
    }

    private function fillMysqlMetrics(array &$metrics): void
    {
        try {
            $metrics['version'] = DB::selectOne('SELECT VERSION() AS version')?->version;
        } catch (\Throwable $e) {
        }

        try {
            $size = DB::selectOne('
                SELECT SUM(data_length + index_length) / 1048576 AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = DATABASE()
            ');
            $metrics['size_mb'] = $size?->size_mb ? round((float) $size->size_mb, 2) : null;
        } catch (\Throwable $e) {
        }

        try {
            $conn = DB::selectOne("SHOW STATUS LIKE 'Threads_connected'");
            $metrics['active_connections'] = $conn->Value ?? null;
        } catch (\Throwable $e) {
        }

        try {
            $metrics['tables'] = collect(DB::select('
                SELECT table_name, table_rows AS row_count,
                       (data_length + index_length) / 1048576 AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = DATABASE()
                ORDER BY size_mb DESC
                LIMIT 10
            '))->map(fn ($row) => [
                'name' => $row->table_name ?? $row->TABLE_NAME,
                'rows' => (int) ($row->row_count ?? $row->ROW_COUNT ?? 0),
                'size_mb' => (float) ($row->size_mb ?? $row->SIZE_MB ?? 0),
            ])->all();
        } catch (\Throwable $e) {
        }
    }

    private function pendingMigrationsCount(): ?int
    {
        try {
            $ran = DB::table('migrations')->pluck('migration')->all();
            $files = collect(glob(database_path('migrations/*.php')))
                ->map(fn ($path) => basename($path, '.php'))
                ->all();

            return count(array_diff($files, $ran));
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function storageChecks(): array
    {
        return [
            ['label' => 'storage/', 'writable' => is_writable(storage_path())],
            ['label' => 'storage/logs/', 'writable' => is_writable(storage_path('logs'))],
            ['label' => 'bootstrap/cache/', 'writable' => is_writable(base_path('bootstrap/cache'))],
            ['label' => 'public/storage (symlink)', 'writable' => file_exists(public_path('storage'))],
        ];
    }
}
