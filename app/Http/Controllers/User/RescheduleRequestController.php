<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\RescheduleRequest;
use App\Notifications\UserRescheduleRequestNotification;
use App\Models\User;

class RescheduleRequestController extends Controller
{
    public function index() {
        $user = auth()->user();

        $available_reservation_for_reschedule = Reservation::with(['user', 'accommodation'])
            ->where('user_id', $user->id)
            ->where('status', 'APPROVED')
            ->where('rebook_count', '<', 2)
            ->where('remaining_balance', '=', 0)
            ->whereDate('check_in_date_time', '>=', now()->addWeek())
            ->whereDoesntHave('rescheduleRequests', function($query) {
                $query->where('status', 'PENDING');
            })
            ->get();

        $rescheduleRequests = RescheduleRequest::whereHas('reservation', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('reservation')->orderBy('created_at', 'desc')->paginate(6);

        $accommodationIds = $available_reservation_for_reschedule->pluck('accommodation_id')
            ->unique()
            ->filter();

        $fullyBookedByAccommodation = [];

        foreach ($accommodationIds as $id) {
            $fullyBookedByAccommodation[$id] = Reservation::getFullyBookedDates(accommodation_id: $id);
        }

        # dd($fullyBookedByAccommodation);

        return view('user.reschedule_request', [
            'rescheduleRequests' => $rescheduleRequests,
            'available_reservation_for_reschedule' => $available_reservation_for_reschedule,
            'fullyBookedByAccommodation' => $fullyBookedByAccommodation,
        ]);
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'reservation_id' => ['required', 'exists:reservations,id'],
            'new_check_in_date_time' => ['required', 'date'],
            'new_check_out_date_time' => ['required', 'date', 'after:today'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        # dd($validated);

        try {
            DB::beginTransaction();

            $user = auth()->user();

            $reservation = Reservation::with(['accommodation', 'user'])
                ->findOrFail($validated['reservation_id']);

            $existingRequest = RescheduleRequest::where('reservation_id', $reservation->id)
                ->where('status', 'PENDING')
                ->first();

            if ($existingRequest) {
                return redirect()
                    ->back()
                    ->with('error', 'A pending reschedule request already exists for this reservation.');
            }

            $rescheduleRequest = RescheduleRequest::create($validated);

            $admins = User::where('userType', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new UserRescheduleRequestNotification($reservation, $rescheduleRequest, $user));
            }
            
            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Your reschedule request has been submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An error occurred while submitting the reschedule request.');
        }
    }

    public function destroy($id) {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $rescheduleRequest = RescheduleRequest::findOrFail($id);

            # dd($rescheduleRequest);

            if ($rescheduleRequest->reservation->user_id !== $user->id) {
                return redirect()
                    ->back()
                    ->with('error', 'You are not authorized to delete this reschedule request.');
            }

            if ($rescheduleRequest->status !== 'PENDING' && $rescheduleRequest->status !== 'DENIED') {
                return redirect()
                    ->back()
                    ->with('error', 'You can only delete pending or denied reschedule requests.');
            }

            $rescheduleRequest->delete();

            DB::commit();

            return redirect()
            ->back()
            ->with('success', 'Reschedule request deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An error occurred while deleting the reschedule request.');
        }
    }
}
