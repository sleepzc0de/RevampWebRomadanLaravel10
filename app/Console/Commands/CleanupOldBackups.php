<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class CleanupOldBackups extends Command
{
    protected $signature = 'backup:cleanup';
    protected $description = 'Remove backups older than 1 week';

    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    public function handle()
    {
        try {
            $this->info('Starting backup cleanup...');

            $this->backupService->cleanOldBackups();

            $this->info('Cleanup completed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Cleanup failed: ' . $e->getMessage());
            return 1;
        }
    }
}
