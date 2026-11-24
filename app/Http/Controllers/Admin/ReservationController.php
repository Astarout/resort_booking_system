<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReservationApprovedNotification;

class ReservationController extends Controller
{
    public function index() {
        $reservations = Reservation::with(['accommodation', 'user'])
            ->orderByRaw("
                CASE
                    WHEN UPPER(TRIM(status)) = 'PENDING' THEN 1
                    WHEN UPPER(TRIM(status)) = 'APPROVED' THEN 2
                    WHEN UPPER(TRIM(status)) = 'DONE' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('created_at')
            ->paginate(20);

        # dd($reservations->toArray());

        return view('admin.reservations', [
            'reservations' => $reservations,
        ]);
    }

    public function destroy($id) {

        DB::beginTransaction();

        try {
            $reservation = Reservation::findOrFail($id);

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

    public function approve($id) {

        if (Auth::user()->usertype !== 'admin') {
            abort(409, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            $reservation = Reservation::with('user', 'accommodation')->findOrFail($id);

            $validIdPath = $reservation->valid_id;

            $reservation->status = 'approved';
            $reservation->valid_id = null;
            $reservation->save();

            if ($validIdPath && Storage::disk('private')->exists($validIdPath)) {
                Storage::disk('private')->delete($validIdPath);
            }
            
            $reservation->user->notify(new ReservationApprovedNotification($reservation));

            DB::commit();

            return redirect()
            ->back()
            ->with('success', 'Reservation approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
            ->back()
            ->with('error', 'Failed to approve reservation.');
        }
    }

    public function showValidID($id) {
        try {
            if (Auth::user()->usertype !== 'admin') {
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
}
