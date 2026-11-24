<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RescheduleRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Notifications\RescheduleApprovedNotification;
use App\Notifications\RescheduleDeniedNotification;

class RescheduleRequestController extends Controller
{
    public function index() {

        $rescheduleRequests = RescheduleRequest::with( 'reservation')
            ->orderByRaw("FIELD(status, 'PENDING', 'APPROVED', 'DENIED')")
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        # dd($rescheduleRequests);

        return view('admin.reschedule_request', [
            'rescheduleRequests' => $rescheduleRequests
        ]);
    }

    public function approve($id) {
       DB::beginTransaction();

        try {
            $rescheduleRequest = RescheduleRequest::findOrFail($id);

            $rescheduleRequest->status = 'APPROVED';

            $reservation = Reservation::findOrFail($rescheduleRequest->reservation_id);

            $reservation->check_in_date_time = $rescheduleRequest->new_check_in_date_time;
            $reservation->check_out_date_time = $rescheduleRequest->new_check_out_date_time;

            $reservation->rebook_count = $reservation->rebook_count + 1;

            $rescheduleRequest->save();
            $reservation->save();

            $reservation->user->notify(new RescheduleApprovedNotification($reservation, $rescheduleRequest));

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Reschedule request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to approve request.');
        }
    }

    public function deny($id) {
        DB::beginTransaction();

        try {
            $rescheduleRequest = RescheduleRequest::findOrFail($id);

            $rescheduleRequest->status = 'DENIED';

            $reservation = Reservation::findOrFail($rescheduleRequest->reservation_id);
            $reservation->user->notify(new RescheduleDeniedNotification($reservation));

            $rescheduleRequest->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Reschedule request denied successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to deny request.');
        }
    }
}
