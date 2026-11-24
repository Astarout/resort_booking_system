<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\ReservationCancelledNotification;

class RecordController extends Controller
{
    public function index() {
        $user = Auth::user();

        $reservations = Reservation::with(['accommodation', 'user'])
            ->where('user_id', $user->id)
            ->orderByRaw("
                CASE
                    WHEN status = 'PENDING' THEN 1
                    WHEN status = 'APPROVED' THEN 2
                    WHEN status = 'DONE' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('created_at')
            ->paginate(15);

        $accommodationIds = $reservations->pluck('accommodation_id')->unique()->filter();

        $fullyBookedByAccommodation = [];

        foreach ($accommodationIds as $id) {
            $fullyBookedByAccommodation[$id] = Reservation::getFullyBookedDates($id);
        }

        # dd($reservations->toArray(), $fullyBookedByAccommodation);

        return view('user.record', [
            'reservations' => $reservations,
            'fullyBookedByAccommodation' => $fullyBookedByAccommodation,
        ]);
    }

    public function destroy($id) {
        DB::beginTransaction();

        try {
            $reservation = Reservation::findOrFail($id);

            if ($reservation->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if ($reservation->status === 'APPROVED' || $reservation->paid_balance > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete an approved reservation.');
            }

            $validIdPath = $reservation->valid_id;

            $reservation->valid_id = null;

            if ($validIdPath && Storage::disk('private')->exists($validIdPath)) {
                Storage::disk('private')->delete($validIdPath);
            }

            $reservation->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Reservation deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to delete reservation.');
        }
    }

    public function showValidID($id) {
        try {
            if (Auth::user()->usertype !== 'user') {
                abort(403, 'Unauthorized access.');
            }

            $reservation = Reservation::findOrFail($id);

            if (!$reservation->valid_id) {
                abort(404, 'Valid ID not found.');
            }

            $path = storage_path('app/private/' . $reservation->valid_id);

            if (!file_exists($path)) {
                abort(404, 'File not found.');
            }

            return response()
                ->file($path);
        } catch (\Exception $e) {
            abort(404, 'Unable to display the valid ID.');
        }
    }

    public function cancel($id) {
        DB::beginTransaction();

        try {
            $reservation = Reservation::findOrFail($id);

            if ($reservation->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (!in_array($reservation->status, ['PENDING', 'APPROVED'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Only PENDING or APPROVED reservations can be cancelled.');
            }
            
            $validIdPath = $reservation->valid_id;

            $reservation->status = 'CANCELLED';
            $reservation->valid_id = null;

            $reservation->save();
            
            if ($validIdPath && Storage::disk('private')->exists($validIdPath)) {
                Storage::disk('private')->delete($validIdPath);
            }
            
            $admins = User::where('usertype', 'admin')->get();
            
            foreach ($admins as $admin) {
                $admin->notify(new ReservationCancelledNotification($reservation));
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Reservation cancelled successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to cancel reservation.');
        }
    }
}
