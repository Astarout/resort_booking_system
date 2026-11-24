<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationTimeLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpectedGuestsController extends Controller
{
    public function index()
    {
        $todaysGuests = Reservation::with(['accommodation', 'log', 'user'])
            ->whereIn('reservations.status', ['APPROVED', 'DONE'])
            ->orderBy('check_in_date_time', 'asc')
            ->get()
            ->filter(function ($r) {
                // Exclude guests who are already checked out
                if ($r->log?->date_and_time_out) {
                    return false;
                }

                $now = Carbon::now();
                // $now = Carbon::parse('23-11-2025'); // For testing

                $checkInDateTime = Carbon::parse($r->check_in_date_time);
                $checkOutDateTime = Carbon::parse($r->check_out_date_time);

                // Check if current date falls within the stay range
                return $now->between(
                    $checkInDateTime->copy()->startOfDay(),
                    $checkOutDateTime->copy()->endOfDay()
                );
            })
            ->groupBy('user_id');

        // Flags for Blade
        foreach ($todaysGuests as $userReservations) {
            foreach ($userReservations as $reservation) {
                $reservation->time_in_logged = $reservation->log?->date_and_time_in ? true : false;
                $reservation->time_out_logged = $reservation->log?->date_and_time_out ? true : false;
            }
        }

        $logs = ReservationTimeLog::with(['reservation.user', 'reservation.accommodation'])
            ->orderByDesc('date_and_time_in')
            ->paginate(10);

        return view('admin.expectedGuests', [
            'todaysGuests' => $todaysGuests,
            'logs' => $logs,
        ]);
    }

    public function time_in(Request $request)
    {
        $reservation = Reservation::with('log')->findOrFail($request->reservation_id);

        DB::beginTransaction();

        try {
            if (!$reservation->log) {
                ReservationTimeLog::create([
                    'reservation_id' => $reservation->id,
                    'date_and_time_in' => now(),
                    'log_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return back()
                ->with('success', 'Time in recorded.');
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()
                ->with('error', 'Failed to record time in.');
        }
    }

    public function time_out(Request $request)
    {
        $reservation = Reservation::with('log', 'accommodation')->findOrFail($request->reservation_id);

        DB::beginTransaction();

        try {
            if ($reservation->log && !$reservation->log->date_and_time_out) {

                $timeOut = now();
                
                $log = $reservation->log;

                // NEW: Uses real checkout datetime
                $scheduledCheckout = Carbon::parse($reservation->check_out_date_time);

                // Calculate late hours
                $lateHours = $timeOut->greaterThan($scheduledCheckout)
                    ? (int) ceil($scheduledCheckout->diffInMinutes($timeOut) / 60)
                    : 0;

                $additionalFee = $lateHours * 200;

                // Update log fields
                $log->date_and_time_out = $timeOut;
                $log->status = $lateHours > 0 ? 'late' : 'on time';
                $log->additional_fee = $additionalFee;
                $log->log_by = auth()->id();
                $log->save();
            }

            DB::commit();

            return back()
                ->with('success', 'Time out recorded.');
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()
                ->with('error', 'Failed to record time out.');
        }
    }

    public function markFeePaid(Request $request)
    {
        $reservationLog = ReservationTimeLog::findOrFail($request->reservation_log_id);

        DB::beginTransaction();

        try {
            $reservationLog->is_paid = true;
            $reservationLog->log_by = auth()->id();
            $reservationLog->save();

            DB::commit();

            return back()
                ->with('success', 'Additional fee marked as paid.');
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()
                ->with('error', 'Failed to mark additional fee as paid.');
        }
    }
}
