<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Notifications\ReservationCompletedNotification;

class MarkReservationsAsDone extends Command
{
    protected $signature = 'app:mark-reservations-as-done';
    protected $description = 'Automatically mark reservations as DONE when check-out datetime has passed.';

    public function handle()
    {
        $updatedCount = 0;
        $updatedIds = [];

        // Only process APPROVED reservations whose check-out time is already passed
        Reservation::with(['accommodation', 'user'])
            ->where('status', 'APPROVED')
            ->where('check_out_date_time', '<=', Carbon::now()) // Main condition
            ->chunk(100, function ($reservations) use (&$updatedCount, &$updatedIds) {

                foreach ($reservations as $reservation) {

                    // Safety: must have related user & accommodation
                    if (!$reservation->user || !$reservation->accommodation) {
                        continue;
                    }

                    // Mark as DONE
                    $reservation->update([
                        'status' => 'DONE',
                    ]);

                    // Notify the user
                    $reservation->user->notify(
                        new ReservationCompletedNotification($reservation)
                    );

                    $updatedCount++;
                    $updatedIds[] = $reservation->id;
                }
            });

        // Console output
        $this->info("Reservations checked. $updatedCount marked as DONE.");

        if ($updatedCount > 0) {
            $this->info("Updated Reservation IDs: " . implode(', ', $updatedIds));
        }
    }
}
