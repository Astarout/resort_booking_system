<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefundRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\RefundRequestSubmittedNotification;

class RefundRequestController extends Controller
{
    public function index() {
        $user = auth()->user();

        $available_reservations_for_refund = Reservation::with(['user', 'accommodation', 'refund_request'])
            ->where('user_id', auth()->id())
            ->where('status', 'CANCELLED')
            ->whereDoesntHave('refund_request')
            ->get();

        $refund_requests = RefundRequest::whereHas('reservation', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('reservation')->orderBy('created_at', 'desc')->paginate(6);

        return view( 'user.refund_request', [
            'available_reservations_for_refund' => $available_reservations_for_refund,
            'refund_requests' => $refund_requests,
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'reservation_id' => ['required', 'exists:reservations,id'],
            'refund_amount' => ['required', 'numeric', 'min:0'],
            'mode' => ['required', 'in:walk-in,gcash'],
            'gcash_number' => ['nullable', 'regex:/^09\d{9}$/'],
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Get reservation, ensure it belongs to user and is CANCELLED
            $reservation = Reservation::where('id', $validated['reservation_id'])
                ->where('user_id', $user->id)
                ->where('status', 'CANCELLED')
                ->first();

            if (!$reservation) {
                return redirect()->back()->with('error', 'Invalid reservation for refund.');
            }

            // Check for existing pending refund
            $existingRequest = RefundRequest::where('reservation_id', $reservation->id)
                ->whereIn('status', ['PENDING', 'COMPLETED'])
                ->first();

            if ($existingRequest) {
                return redirect()
                    ->back()
                    ->with('error', 'A pending refund request already exists for this reservation.');
            }

            // Create refund request
            $refund = RefundRequest::create([
                'reservation_id' => $validated['reservation_id'],
                'refund_amount' => $validated['refund_amount'],
                'mode' => $validated['mode'],
                'gcash_number' => $validated['mode'] === 'gcash' ? $validated['gcash_number'] : null,
                'screen_shot' => null,
                'remarks' => null,
                'status' => 'pending',
            ]);

            $admins = User::where('usertype', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new RefundRequestSubmittedNotification($refund));
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Your refund request has been submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An error occurred while submitting the refund request.');
        }
    }
}
