<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Notifications\TodayReservationNotification;

class SendTodayReservationNotifications extends Command
{
    protected $signature = 'app:send-today-reservation-notifications';
    protected $description = 'Command description';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $reservations = Reservation::whereDate('check_in_date_time', $today)
            ->whereIn('status', ['APPROVED'])
            ->with(['user', 'accommodation'])
            ->get();

        if ($reservations->isEmpty()) {
            $message = "No reservations for today.";
            $this->info($message);
            return $message;
        }

        foreach ($reservations as $reservation) {
            if ($reservation->user) {
                $reservation->user->notify(new TodayReservationNotification($reservation));
                $this->info("Notification sent to {$reservation->user->email}");
            }
        }

        $message = "All notifications have been sent successfully.";
        $this->info($message);
        return $message;
    }
}
