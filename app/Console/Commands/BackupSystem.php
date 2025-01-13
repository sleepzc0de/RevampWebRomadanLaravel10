<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupSystem extends Command
{
    protected $signature = 'backup:run';
    protected $description = 'Create backup of database and application files';

    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    public function handle()
    {
        try {
            $this->info('Starting backup process...');

            // Execute backup
            $this->backupService->createBackup();

            $this->info('Backup completed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return 1;
        }
    }
}
