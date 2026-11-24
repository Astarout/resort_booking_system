<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\RefundRequest;
use App\Notifications\RefundRequestCompletedNotification;

class RefundRequestController extends Controller
{
    public function index()
    {
        $refund_requests = RefundRequest::with('reservation')
            ->orderByRaw("FIELD(status, 'PENDING', 'COMPLETED')")
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('admin.refund_request', [
            'refund_requests' => $refund_requests,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate only screen_shot and remarks
        $validated = $request->validate([
            'screen_shot' => ['nullable', 'image', 'max:2048'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::beginTransaction();

            $refundRequest = RefundRequest::with('reservation.user')->findOrFail($id);

            $screenshotPath = $refundRequest->screen_shot;
            if ($request->hasFile('screen_shot')) {
                $file = $request->file('screen_shot');
                $screenshotPath = $file->store('screenshots', 'public');
            }

            $refundRequest->update([
                'status' => 'COMPLETED',
                'screen_shot' => $screenshotPath,
                'remarks' => $validated['remarks'] ?? 'COMPLETED',
            ]);
            
            $refundRequest->reservation->user->notify(new RefundRequestCompletedNotification($refundRequest));

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Refund request has been marked as completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An error occurred while updating the refund request.');
        }
    }
}
