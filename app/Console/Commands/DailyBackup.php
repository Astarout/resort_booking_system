<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class DailyBackup extends Command
{
    protected $signature = 'app:backup-daily';
    protected $description = 'Run daily backup';

    public function handle()
    {
        try {
            # $exitCode = Artisan::call('backup:run');
            $exitCode = Artisan::call('backup:run', ['--only-db' => true,]);

            $output = Artisan::output();

            if ($exitCode !== 0) {
                $this->error('Backup failed: ' . $output);
            } else {
                $this->info('Backup completed successfully.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
