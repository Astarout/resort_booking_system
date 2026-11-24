<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PayMongoService;
use App\Models\Reservation;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessNotification;

class PaymentController extends Controller
{
    protected $paymongo;

    public function __construct(PayMongoService $paymongo)
    {
        $this->paymongo = $paymongo;
    }

    public function index()
    {
        $user_id = Auth::id();

        $reservations = Reservation::with(['user', 'accommodation'])
            ->where('user_id', $user_id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where('remaining_balance', '>', 0)
            ->paginate(15);

        return view('user.payments.payments', [
            'reservations' => $reservations,
        ]);
    }

    public function viewTransactions()
    {
        $user_id = Auth::id();

        $transactions = Transaction::with(['reservation', 'reservation.user', 'reservation.accommodation', 'user'])
            ->whereHas('reservation', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return view('user.payments.transactions', [
            'transactions' => $transactions,
        ]);
    }

    public function pay(Request $request, $reservationId) {
        $reservation = Reservation::findOrFail($reservationId);
        $amount = $request->input('custom_amount');

        if ($amount > $reservation->remaining_balance) {
            return back()->with('error', 'Amount exceeds remaining balance.');
        }

        $amountInCentavos = $amount * 100;

        $successUrl = route('user.payments.success', $reservationId);
        $failedUrl = route('user.payments.failed', $reservationId);

        $billing = [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ];

        # Create GCash source
        $source = $this->paymongo->createGCashSource($amountInCentavos, $successUrl, $failedUrl, $billing);

        Log::info('PayMongo source created', [
            'reservation_id' => $reservation->id,
            'source' => $source
        ]);

        if (!isset($source['source_id'], $source['checkout_url']) || !$source['source_id']) {
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }

        # Store source_id in reservation for verification later
        $reservation->update([
            'paymongo_source_id' => $source['source_id'],
        ]);

        # Redirect user to GCash checkout
        return redirect($source['checkout_url']);
    }

    public function success($reservationId) {
        $reservation = Reservation::findOrFail($reservationId);

        if (!$reservation->paymongo_source_id) {
            return redirect()->route('user.payments')
                ->with('error', 'No payment source found for this reservation.');
        }

        # Get source status from PayMongo
        $source = $this->paymongo->getSource($reservation->paymongo_source_id);
        $status = $source['data']['attributes']['status'] ?? null;

        Log::info('PayMongo source status', [
            'reservation_id' => $reservation->id,
            'status' => $status,
            'source' => $source
        ]);

        # if source is chargeable create the payment
        if ($status === 'chargeable') {
            $amount = $source['data']['attributes']['amount'] ?? 0;
            $sourceId = $source['data']['id'];

            # Create the payment
            $payment = $this
                ->paymongo
                ->createPaymentFromSource(
                    $sourceId,
                    $amount,
                    'GCash Payment for ' . $reservation->id . ' - '.
                        $reservation->accommodation->name .
                        ' By ' . $reservation->user->first_name . ' ' . $reservation->user->last_name . '.'
                );

            # dd($payment);

            Log::info('PayMongo payment created', ['payment' => $payment]);

            # After creation the payment should be paid
            $paymentStatus = $payment['data']['attributes']['status'] ?? null;
            $amountPaid = ($payment['data']['attributes']['amount'] ?? 0) / 100;

            if ($paymentStatus === 'paid') {
                DB::transaction(function () use ($reservation, $amountPaid) {

                    $newPaid = $reservation->paid_balance + $amountPaid;
                    $remaining = $reservation->remaining_balance - $amountPaid;
                    $paymentStatus = $remaining <= 0 ? 'paid' : 'partial';

                    Transaction::create([
                        'reservation_id' => $reservation->id,
                        'payment_method' => 'gcash',
                        'amount' => $amountPaid,
                        'change_amount' => 0,
                        'payment_status' => $paymentStatus,
                        'recorded_by' => Auth::id(),
                    ]);

                    $reservation->paid_balance = $newPaid;
                    $reservation->remaining_balance = max(0, $remaining);
                    $reservation->save();
                });

                $reservation->user->notify(new PaymentSuccessNotification($reservation, $amountPaid, 'GCash'));

                return redirect()
                    ->route('user.payments')->with('success', 'Payment successful!');
            }

            return redirect()->route('user.payments')
                ->with('info', 'Payment created but still processing!');
        }

        # If its already charged or paid just confirm it
        if (in_array($status, ['charged', 'paid', 'success'])) {
            return redirect()
                ->route('user.payments')
                ->with('success', 'Payment successful!');
        }

        return redirect()
            ->route('user.payments')
            ->with('error', 'Payment not completed yet. Please try again.');
    }


    public function failed() {
        return redirect()->route('user.payments')
            ->with('error', 'Payment failed or canceled.');
    }
}
