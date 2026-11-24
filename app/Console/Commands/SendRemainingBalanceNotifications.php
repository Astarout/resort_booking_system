<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\RemainingBalanceNotification;

class SendRemainingBalanceNotifications extends Command
{
    protected $signature = 'app:send-remaining-balance-notifications';
    protected $description = 'Command description';

    public function handle()
    {
        $reservations = Reservation::with(['user', 'accommodation'])
            ->whereIn('status', ['APPROVED', 'PENDING'])
            ->where('remaining_balance', '>', 0)
            ->get();


        foreach ($reservations as $reservation) {
            if ($reservation->user) {
                $reservation->user->notify(new RemainingBalanceNotification($reservation));
            }
        }

        $this->info('Remaining balance notifications sent successfully!');
    }
}
