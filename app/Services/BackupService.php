<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;
use ZipArchive;

class BackupService
{
    protected $backupPath;

    protected $isWindows;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if (! file_exists($this->backupPath)) {
            if (! mkdir($this->backupPath, 0755, true)) {
                Log::error('Failed to create backup directory', ['path' => $this->backupPath]);
                throw new \Exception('Failed to create backup directory');
            }
        }

        if (! is_writable($this->backupPath)) {
            Log::error('Backup directory is not writable', ['path' => $this->backupPath]);
            throw new \Exception('Backup directory is not writable');
        }
    }

    public function createBackup(): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFileName = "backup_{$timestamp}.zip";
        $backupFilePath = "{$this->backupPath}/{$backupFileName}";
        $databaseFileName = "{$timestamp}_database.{$this->databaseFileExtension()}";
        $databaseFilePath = "{$this->backupPath}/{$databaseFileName}";

        try {
            // Backup database
            $this->backupDatabase($databaseFilePath);

            // Create ZIP archive
            $zip = new ZipArchive;
            if ($zip->open($backupFilePath, ZipArchive::CREATE) !== true) {
                throw new \Exception('Cannot create zip file');
            }

            // Add database backup to zip
            if (file_exists($databaseFilePath)) {
                $zip->addFile($databaseFilePath, $databaseFileName);
            }

            // Add application files
            $finder = new Finder;
            $finder->files()->in(base_path())->exclude(['vendor', 'node_modules', 'storage']);
            foreach ($finder as $file) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }

            $zip->close();

            // Delete temporary database file
            if (file_exists($databaseFilePath)) {
                unlink($databaseFilePath);
            }

            return $backupFileName;
        } catch (\Exception $e) {
            // Cleanup on failure
            if (file_exists($databaseFilePath)) {
                unlink($databaseFilePath);
            }
            if (file_exists($backupFilePath)) {
                unlink($backupFilePath);
            }

            Log::error("Error during backup creation: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Ekstensi file dump database mentah sebelum dimasukkan ke zip.
     * SQL Server menghasilkan file backup native biner (.bak), sedangkan
     * MySQL/Postgres menghasilkan dump SQL teks biasa (.sql).
     */
    protected function databaseFileExtension(): string
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return $driver === 'sqlsrv' ? 'bak' : 'sql';
    }

    protected function backupDatabase(string $outputPath)
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        switch ($driver) {
            case 'sqlsrv':
                return $this->backupSqlServer($outputPath);
            case 'mysql':
                return $this->backupMySql($outputPath);
            case 'pgsql':
                return $this->backupPostgres($outputPath);
            default:
                throw new \Exception("Database driver {$driver} is not supported for backup");
        }
    }

    protected function backupSqlServer(string $outputPath)
    {
        $server = config('database.connections.sqlsrv.host');
        $port = config('database.connections.sqlsrv.port', '1433');
        $database = config('database.connections.sqlsrv.database');
        $username = config('database.connections.sqlsrv.username');
        $password = config('database.connections.sqlsrv.password');

        if ($this->isWindows) {
            // Windows backup using sqlcmd.
            // Password lewat env SQLCMDPASSWORD agar tidak terlihat di process list.
            $serverAddress = str_contains($server, '\\') ? $server : "{$server},{$port}";
            $escapedDb = str_replace(']', ']]', $database);

            // BACKUP DATABASE dieksekusi oleh proses service SQL Server itu
            // sendiri (bukan proses PHP), yang biasanya tidak punya akses
            // tulis ke folder storage aplikasi di dalam profil user Windows.
            // Backup dulu ke Windows Temp (writable oleh service manapun),
            // baru dipindahkan ke lokasi akhir oleh proses PHP.
            $sqlServerTempPath = rtrim((string) (getenv('SystemRoot') ?: 'C:\\Windows'), '\\').'\\Temp\\'.basename($outputPath);
            $escapedTempPath = str_replace("'", "''", $sqlServerTempPath);

            // NB: sqlcmd.exe (native ODBC client) salah mem-parsing argumen
            // ketika proc_open() diberi array command di Windows — flag
            // pendek seperti -S berujung "Unknown Option" karena escaping
            // command-line Windows PHP tidak cocok dengan parser argv
            // sqlcmd. Command string yang di-escape manual terbukti bekerja
            // dengan benar, jadi dipakai khusus untuk sqlcmd di sini.
            $command = 'sqlcmd -S '.escapeshellarg($serverAddress)
                .' -U '.escapeshellarg($username)
                .' -C -b -Q '.escapeshellarg("BACKUP DATABASE [{$escapedDb}] TO DISK = N'{$escapedTempPath}' WITH FORMAT");

            $this->runCommand($command, ['SQLCMDPASSWORD' => (string) $password], null, 'sqlcmd');

            if (! @rename($sqlServerTempPath, $outputPath)) {
                throw new \Exception("Gagal memindahkan file backup dari {$sqlServerTempPath} ke {$outputPath}");
            }

            return true;
        }

        // Linux backup using PHP SQL queries
        try {
            $tables = DB::select("SELECT name FROM sys.tables WHERE type = 'U'");
            $output = '-- SQL Server Backup Generated '.date('Y-m-d H:i:s')."\n\n";

            foreach ($tables as $table) {
                // Get table creation SQL
                $tableName = $table->name;
                $createTable = DB::select("SELECT OBJECT_DEFINITION (OBJECT_ID(N'$tableName')) AS CreateTable");
                $output .= $createTable[0]->CreateTable.";\n\n";

                // Get table data (escaping T-SQL: gandakan tanda kutip tunggal)
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $values = implode(', ', array_map(function ($value) {
                        if ($value === null) {
                            return 'NULL';
                        }

                        return "N'".str_replace("'", "''", (string) $value)."'";
                    }, (array) $row));
                    $output .= "INSERT INTO [$tableName] VALUES ($values);\n";
                }
                $output .= "\n";
            }

            file_put_contents($outputPath, $output);

            return true;
        } catch (\Exception $e) {
            Log::error('Database backup error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function backupMySql(string $outputPath)
    {
        // Password lewat env MYSQL_PWD agar tidak terlihat di process list
        $this->runCommand(
            [
                'mysqldump',
                '--host='.config('database.connections.mysql.host'),
                '--port='.config('database.connections.mysql.port'),
                '--user='.config('database.connections.mysql.username'),
                config('database.connections.mysql.database'),
            ],
            ['MYSQL_PWD' => (string) config('database.connections.mysql.password')],
            $outputPath
        );

        return true;
    }

    protected function backupPostgres(string $outputPath)
    {
        // Password lewat env PGPASSWORD agar tidak terlihat di process list
        $this->runCommand(
            [
                'pg_dump',
                '-h', config('database.connections.pgsql.host'),
                '-p', (string) config('database.connections.pgsql.port'),
                '-U', config('database.connections.pgsql.username'),
                '-F', 'p',
                config('database.connections.pgsql.database'),
            ],
            ['PGPASSWORD' => (string) config('database.connections.pgsql.password')],
            $outputPath
        );

        return true;
    }

    /**
     * Jalankan perintah eksternal dengan kredensial via environment variable
     * dan pengecekan exit code (kegagalan tidak pernah silent).
     *
     * Command berupa array dijalankan TANPA shell (argv langsung) sehingga
     * bebas masalah quoting/injection. Command berupa string (dengan setiap
     * bagian dinamis sudah di-escapeshellarg()) dipakai untuk tool yang
     * argv-nya tidak diparsing dengan benar lewat proc_open() array di
     * Windows (lihat komentar pada pemanggil sqlcmd di backupSqlServer()).
     *
     * @param  array<int, string>|string  $command
     */
    protected function runCommand(array|string $command, array $env = [], ?string $stdoutFile = null, ?string $commandLabel = null): void
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => $stdoutFile ? ['file', $stdoutFile, 'w'] : ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $label = $commandLabel ?? (is_array($command) ? $command[0] : $command);

        $process = proc_open($command, $descriptors, $pipes, null, array_merge(getenv(), $env));

        if (! is_resource($process)) {
            throw new \Exception("Failed to start backup process: {$label}");
        }

        fclose($pipes[0]);

        if (isset($pipes[1])) {
            stream_get_contents($pipes[1]);
            fclose($pipes[1]);
        }

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            Log::error('Backup command failed', [
                'command' => $label,
                'exit_code' => $exitCode,
                'stderr' => $stderr,
            ]);

            throw new \Exception("Backup command {$label} failed (exit code {$exitCode})");
        }
    }

    public function deleteOldBackups(int $days): void
    {
        $files = Storage::files('backups');
        foreach ($files as $file) {
            $lastModified = Storage::lastModified($file);
            if (Carbon::createFromTimestamp($lastModified)->diffInDays(now()) > $days) {
                Storage::delete($file);
            }
        }
    }

    public function cleanOldBackups()
    {
        $this->deleteOldBackups(7);
    }
}
