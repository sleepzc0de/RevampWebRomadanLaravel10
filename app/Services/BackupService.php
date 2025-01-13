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

    // Check directory permissions
    if (!file_exists($this->backupPath)) {
        if (!mkdir($this->backupPath, 0755, true)) {
            Log::error('Failed to create backup directory', ['path' => $this->backupPath]);
            throw new \Exception('Failed to create backup directory');
        }
    }

    // Verify directory is writable
    if (!is_writable($this->backupPath)) {
        Log::error('Backup directory is not writable', ['path' => $this->backupPath]);
        throw new \Exception('Backup directory is not writable');
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
        // Check if sqlcmd exists with full error logging
        exec('where sqlcmd 2>&1', $output, $returnVar);
        if ($returnVar !== 0) {
            Log::error('sqlcmd check failed', ['output' => $output]);
            throw new \Exception('sqlcmd is not installed or not in PATH: ' . implode("\n", $output));
        }

        $server = config('database.connections.sqlsrv.host');
        $port = config('database.connections.sqlsrv.port', '1433');
        $database = config('database.connections.sqlsrv.database');
        $username = config('database.connections.sqlsrv.username');
        $password = config('database.connections.sqlsrv.password');

        // Log connection details (excluding password)
        Log::info('Attempting database backup', [
            'server' => $server,
            'port' => $port,
            'database' => $database,
            'outputPath' => $outputPath
        ]);

        $serverAddress = str_contains($server, '\\') ? $server : "{$server},{$port}";

        // Add error output redirection to command
        $command = sprintf(
            'sqlcmd -S %s -U %s -P %s -Q "BACKUP DATABASE [%s] TO DISK = N\'%s\' WITH FORMAT" 2>&1',
            escapeshellarg($serverAddress),
            escapeshellarg($username),
            escapeshellarg($password),
            $database,
            $outputPath
        );

        // Execute with output capture
        exec($command, $output, $result);

        if ($result !== 0) {
            Log::error('Database backup command failed', [
                'output' => $output,
                'exitCode' => $result,
                'command' => preg_replace('/(-P\s+)[^\s]+/', '$1*****', $command)
            ]);
            throw new \Exception('Database backup failed: ' . implode("\n", $output));
        }

        return true;
    } catch (\Exception $e) {
        Log::error('Database backup error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
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
