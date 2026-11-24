<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteUnverifiedUsers extends Command
{
    protected $signature = 'accounts:delete-unverified-users';
    protected $description = 'Delete unverified user accounts older than 24 hours';

    public function handle()
    {
        $threshold = Carbon::now()->subHours(24);

        $deleteCount = User::whereNull('email_verified_at')
            ->where('created_at', '<', $threshold)
            ->delete();

        if ($deleteCount > 0) {
            $message = "Deleted $deleteCount unverified admin account(s) older than 24 hours.";
        } else {
            $message = "No unverified admin accounts to delete.";
        }

        // Show in terminal
        $this->info("[DeleteUnverifiedUsers] Schedule ran. $message");

        // Log it
        Log::info("[DeleteUnverifiedUsers] Schedule ran. $message");

        return 0;
    }
}
