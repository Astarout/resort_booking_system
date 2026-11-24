<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeleteDeactivatedUsers extends Command
{
    protected $signature = 'app:delete-deactivated-users';
    protected $description = 'Command description';

    public function handle()
    {
         $expired = User::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays(30))
            ->get();

        foreach ($expired as $user) {
            $user->forceDelete();
        }

        $this->info($expired->count().' users permanently deleted.');
    }
}
