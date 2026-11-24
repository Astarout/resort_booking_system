<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Notifications\PaymentSuccessNotification;

class PaymentController extends Controller
{
    public function index() {
        $reservations = Reservation::with(['user', 'accommodation'])
            ->where('remaining_balance', '>', 0)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->paginate(15);

        # dd($reservations);

        return view('admin.payments.payments', [
            'reservations' => $reservations,
        ]);
    }

    public function storePayment(Request $request, $reservation_id) {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0', 'max:20000'],
            'payment_method' => ['required', 'in:walk-in,bank'],
        ]);

        $recorded_by = Auth::id();

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($reservation_id);

            $amountPaid = $validated['amount'];
            $balance = $reservation->remaining_balance ?? 0;

            $change = max($amountPaid - $balance, 0);

            $actualPayment = min($amountPaid, $balance);

            $payment_status = ($amountPaid >= $balance) ? 'paid' : 'partial';

            $reservation->paid_balance = ($reservation->paid_balance ?? 0) + $actualPayment;
            $reservation->remaining_balance = max($balance - $actualPayment, 0);

            Transaction::create([
                'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                'reservation_id' => $reservation->id,
                'payment_method' => $request->input('payment_method'),
                'amount' => $amountPaid,
                'change_amount' => $change,
                'payment_status' => $payment_status,
                'remarks' => $request->input('remarks'),
                'recorded_by' => $recorded_by,
            ]);

            $reservation->save();

            DB::commit();

            $reservation->user->notify(new PaymentSuccessNotification($reservation, $actualPayment, $validated['payment_method']));

            return redirect()
                ->back()
                ->with('success', 'Payment recorded successfully.');
        } catch (QueryException $qe) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Database error occurred. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to record payment. Please try again.');
        }
    }

    public function viewTranscations() {
        $transactions = Transaction::with(['reservation', 'reservation.user', 'reservation.accommodation', 'user'])
            ->orderBy('created_at', 'DESC')
            ->paginate(30);

        # dd($transactions);

        return view('admin.payments.transactions', [
            'transactions' => $transactions,
        ]);
    }
}
