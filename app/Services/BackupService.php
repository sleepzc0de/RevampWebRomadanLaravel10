<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        if (!file_exists($this->backupPath)) {
            if (!mkdir($this->backupPath, 0755, true)) {
                Log::error('Failed to create backup directory', ['path' => $this->backupPath]);
                throw new \Exception('Failed to create backup directory');
            }
        }

        if (!is_writable($this->backupPath)) {
            Log::error('Backup directory is not writable', ['path' => $this->backupPath]);
            throw new \Exception('Backup directory is not writable');
        }
    }

    public function createBackup(): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFileName = "backup_{$timestamp}.zip";
        $backupFilePath = "{$this->backupPath}/{$backupFileName}";
        $databaseFileName = "{$timestamp}_database.sql";
        $databaseFilePath = "{$this->backupPath}/{$databaseFileName}";

        try {
            // Backup database
            $this->backupDatabase($databaseFilePath);

            // Create ZIP archive
            $zip = new ZipArchive();
            if ($zip->open($backupFilePath, ZipArchive::CREATE) !== true) {
                throw new \Exception('Cannot create zip file');
            }

            // Add database backup to zip
            if (file_exists($databaseFilePath)) {
                $zip->addFile($databaseFilePath, $databaseFileName);
            }

            // Add application files
            $finder = new Finder();
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
            // Windows backup using sqlcmd
            $serverAddress = str_contains($server, '\\') ? $server : "{$server},{$port}";
            $command = sprintf(
                'sqlcmd -S %s -U %s -P %s -Q "BACKUP DATABASE [%s] TO DISK = N\'%s\' WITH FORMAT"',
                escapeshellarg($serverAddress),
                escapeshellarg($username),
                escapeshellarg($password),
                $database,
                $outputPath
            );
        } else {
            // Linux backup using PHP SQL queries
            try {
                $tables = DB::select("SELECT name FROM sys.tables WHERE type = 'U'");
                $output = "-- SQL Server Backup Generated " . date('Y-m-d H:i:s') . "\n\n";

                foreach ($tables as $table) {
                    // Get table creation SQL
                    $tableName = $table->name;
                    $createTable = DB::select("SELECT OBJECT_DEFINITION (OBJECT_ID(N'$tableName')) AS CreateTable");
                    $output .= $createTable[0]->CreateTable . ";\n\n";

                    // Get table data
                    $rows = DB::table($tableName)->get();
                    foreach ($rows as $row) {
                        $columns = implode("','", array_map('addslashes', (array)$row));
                        $output .= "INSERT INTO [$tableName] VALUES ('$columns');\n";
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
    }

    protected function backupMySql(string $outputPath)
    {
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        if ($this->isWindows) {
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s -p%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($outputPath)
            );
        } else {
            $command = sprintf(
                'MYSQL_PWD=%s mysqldump -h %s -P %s -u %s %s > %s',
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($database),
                escapeshellarg($outputPath)
            );
        }

        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            throw new \Exception('MySQL backup failed');
        }

        return true;
    }

    protected function backupPostgres(string $outputPath)
    {
        $host = config('database.connections.pgsql.host');
        $port = config('database.connections.pgsql.port');
        $database = config('database.connections.pgsql.database');
        $username = config('database.connections.pgsql.username');
        $password = config('database.connections.pgsql.password');

        $command = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -p %s -U %s -F p %s > %s',
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($outputPath)
        );

        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            throw new \Exception('PostgreSQL backup failed');
        }

        return true;
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
