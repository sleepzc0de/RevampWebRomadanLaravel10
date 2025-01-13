<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    private BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        $backups = collect(Storage::files('backups'))
            ->map(fn($file) => [
                'name' => basename($file),
                'size' => Storage::size($file),
                'date' => Storage::lastModified($file),
            ])
            ->sortByDesc('date')
            ->values();

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            // Add request validation
            if (!request()->ajax()) {
                throw new \Exception('Invalid request method');
            }

            $backupFile = $this->backupService->createBackup();

            return response()->json([
                'success' => true,
                'message' => "Backup {$backupFile} created successfully.",
                'file' => $backupFile
            ]);
        } catch (\Exception $e) {
            Log::error('Backup creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $fileName)
    {
        try {
            Storage::delete("backups/{$fileName}");
            return redirect()->route('backups.index')
                ->with('success', "Backup {$fileName} deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Backup deletion failed', [
                'file' => $fileName,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors('Failed to delete backup.');
        }
    }

    public function download(string $fileName)
    {
        try {
            $path = Storage::path("backups/{$fileName}");
            if (!Storage::exists("backups/{$fileName}")) {
                throw new \Exception('Backup file not found.');
            }
            return response()->download($path);
        } catch (\Exception $e) {
            Log::error('Backup download failed', [
                'file' => $fileName,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors('Failed to download backup.');
        }
    }

    public function cleanup()
    {
        try {
            $this->backupService->deleteOldBackups(7); // Keep backups for 7 days
            return redirect()->route('backups.index')
                ->with('success', 'Old backups cleaned up successfully.');
        } catch (\Exception $e) {
            Log::error('Backup cleanup failed', [
                'error' => $e->getMessage()
            ]);
            return back()->withErrors('Failed to clean up old backups.');
        }
    }
}
