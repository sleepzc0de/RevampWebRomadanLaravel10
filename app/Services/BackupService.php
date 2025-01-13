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

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public function createBackup(): string
    {
        if (!$this->testSqlServerConnection()) {
            throw new \Exception('Cannot establish connection to SQL Server');
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFileName = "backup_{$timestamp}.zip";
        $backupFilePath = "{$this->backupPath}/{$backupFileName}";
        $databaseFileName = "{$timestamp}_database.bak"; // Menggunakan ekstensi .bak untuk SQL Server
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

            // Delete temporary database file after successful zip creation
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

    private function backupDatabase(string $outputPath)
    {
        try {
            // Check if sqlcmd exists
            exec('where sqlcmd', $output, $returnVar);
            if ($returnVar !== 0) {
                throw new \Exception('sqlcmd is not installed or not in PATH');
            }

            $server = config('database.connections.sqlsrv.host');
            $port = config('database.connections.sqlsrv.port', '1433');
            $database = config('database.connections.sqlsrv.database');
            $username = config('database.connections.sqlsrv.username');
            $password = config('database.connections.sqlsrv.password');

            // Convert path to Windows format
            $outputPath = str_replace('/', '\\', $outputPath);

            // If server contains instance name, don't append port
            $serverAddress = str_contains($server, '\\') ? $server : "{$server},{$port}";

            // Backup command
            $command = sprintf(
                'sqlcmd -S %s -U %s -P %s -Q "BACKUP DATABASE [%s] TO DISK = N\'%s\' WITH FORMAT"',
                escapeshellarg($serverAddress),
                escapeshellarg($username),
                escapeshellarg($password),
                $database,
                $outputPath
            );

            exec($command, $output, $result);

            if ($result !== 0) {
                Log::error('Database backup failed', [
                    'output' => $output,
                    'command' => preg_replace('/(-P\s+)[^\s]+/', '$1*****', $command)
                ]);
                throw new \Exception('Database backup failed: ' . implode("\n", $output));
            }

            // Verify backup file exists and has size
            if (!file_exists($outputPath) || filesize($outputPath) === 0) {
                throw new \Exception('Backup file was not created or is empty');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Database backup error: ' . $e->getMessage());
            throw $e;
        }
    }


    private function zipFiles(Finder $files, string $outputPath)
    {
        $zip = new \ZipArchive();

        if ($zip->open($outputPath, \ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }
            $zip->close();
        } else {
            throw new \Exception('Failed to create ZIP archive.');
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
        $files = Storage::files($this->backupPath);
        $now = Carbon::now();

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::lastModified($file));
            if ($lastModified->diffInDays($now) > 7) {
                Storage::delete($file);
            }
        }
    }
    private function testSqlServerConnection()
{
    try {
        $server = config('database.connections.sqlsrv.host');
        $port = config('database.connections.sqlsrv.port', '1433');
        $username = config('database.connections.sqlsrv.username');
        $password = config('database.connections.sqlsrv.password');

        $serverAddress = str_contains($server, '\\') ? $server : "{$server},{$port}";

        $command = sprintf(
            'sqlcmd -S %s -U %s -P %s -Q "SELECT @@VERSION"',
            escapeshellarg($serverAddress),
            escapeshellarg($username),
            escapeshellarg($password)
        );

        exec($command, $output, $result);

        if ($result === 0) {
            Log::info('SQL Server connection test successful', ['version' => $output[0] ?? 'Unknown']);
            return true;
        } else {
            Log::error('SQL Server connection test failed', ['output' => $output]);
            return false;
        }
    } catch (\Exception $e) {
        Log::error('SQL Server connection test error', ['error' => $e->getMessage()]);
        return false;
    }
}
}
