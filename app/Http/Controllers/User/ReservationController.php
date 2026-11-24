<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\AccommodationGallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Sheet;
use App\Models\Pet;
use App\Models\EntranceFee;
use App\Models\User;
use App\Notifications\NewReservationNotification;

class ReservationController extends Controller
{
    public function index() {
        $accommodations = Accommodation::all();

        $user = Auth::user();

        $myReservations = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        # dd($myReservations);

        return view('user.reservation.reservation', [
            'accommodations' => $accommodations,
            'myReservations' => $myReservations,
        ]);
    }

    public function viewDetails($id) {
        $accommodation = Accommodation::findOrFail($id);

        $entranceFee = EntranceFee::where('tour_type', $accommodation->tour_type)->first();

        $entrance_fee_per_pax = $entranceFee ? $entranceFee->amount : 0;

        $gallery = AccommodationGallery::where('accommodation_id', $id)->get();

        $fullyBookedDates = Reservation::getFullyBookedDates($accommodation->id);

        $accommodation->disabled_dates = $fullyBookedDates;

        # dd($accommodation->disabled_dates);

        return view('user.reservation.view', [
            'accommodation' => $accommodation,
            'gallery' => $gallery,
            'entrance_fee_per_pax' => $entrance_fee_per_pax,
        ]);
    }

    public function reservationForm() {
        $accommodations = Accommodation::all();
        $allowed_pets = Pet::all();
        $available_sheets = Sheet::all();
        $entrance_fees = EntranceFee::all();

        foreach ($accommodations as $accommodation) {
            $accommodation->disabled_dates = Reservation::getFullyBookedDates($accommodation->id);
        }

        # dd($accommodations);

        return view('user.reservation.reservation-form', [
            'accommodations' => $accommodations,
            'allowed_pets' => $allowed_pets,
            'available_sheets' => $available_sheets,
            'entrance_fees' => $entrance_fees,
        ]);
    }

    public function store(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'accommodation_id' => ['required', 'exists:accommodations,id'],
            'check_in_date_time' => ['required',],
            'check_out_date_time' => ['required',],
            'no_of_guest' => [
                'required',
                'min:1',
                function($attribute, $value, $fail) use($request) {
                    $accommodation = Accommodation::find($request->accommodation_id);
                    if ($accommodation && $value > $accommodation->pax) {
                        $fail("The number of guests cannot exceed {$accommodation->pax}.");
                    }
                },
            ],
            'sheet_color' => ['nullable', 'exists:sheets,color'],
            'valid_id' => ['required', 'file', 'max:4096', 'mimes:jpg,jpeg,png'],
            'pets' => ['nullable', 'array',],
            'pets.*' => ['exists:pets,id',],
            'pet_quantities' => ['nullable', 'array',],
        ]);

        $checkIn = \DateTime::createFromFormat('F j, Y \a\t g:i A', $request->check_in_date_time);
        $checkOut = \DateTime::createFromFormat('F j, Y \a\t g:i A', $request->check_out_date_time);

        if (!$checkIn || !$checkOut) {
            return back()
                ->with('error', 'Invalid date format.');
        }

        $checkInDate = (clone $checkIn)->setTime(0, 0, 0);
        $checkOutDate = (clone $checkOut)->setTime(0, 0, 0);

        $interval = $checkInDate->diff($checkOutDate);
        $no_of_days = $interval->days;

        if ($no_of_days === 0) {
            $no_of_days = 1;
        }

        # dd($no_of_days);

        $accommodation = Accommodation::find($validated['accommodation_id']);

        $accommodation_price = $accommodation->price ?? 0;

        $discount_applied = $accommodation->discount ?? 0;
        $discounted_price = $accommodation_price - ($accommodation_price * ($discount_applied / 100));

        $discounted_price = $discounted_price * $no_of_days;

        $entrance_fee_per_pax = 0;

        if (!empty($accommodation->tour_type)) {
            $entrance_fee = EntranceFee::where('tour_type', $accommodation->tour_type)->first();
            $entrance_fee_per_pax = $entrance_fee->amount ?? 0;
        }

        $total_entrance_fee = $entrance_fee_per_pax * ($validated['no_of_guest'] ?? 0);

        $total_price = $discounted_price + $total_entrance_fee;

        # dd($accommodation_price, $discounted_price, $total_entrance_fee, $total_price);

        try {
            DB::beginTransaction();

            $reservation_id = 'CA' . now()->format('YmdHi') . rand(100, 999);

            $validIdPath = $request->file('valid_id')->store('valid_ids', 'private');

            $pets = [];

            if ($request->filled('pets')) {
                foreach ($request->pets as $petId) {
                    $pet = Pet::find($petId);
                    if ($pet) {
                        $quantity = $request->pet_quantities[$petId] ?? 1;
                        $pets[] = [
                            'name' => $pet->name,
                            'quantity' => $quantity,
                        ];
                    }
                }
            }

            $reservation = Reservation::create([
                'id' => $reservation_id,
                'user_id' => $user->id,
                'reservation_date' => now(),
                'check_in_date_time' => $checkIn,
                'check_out_date_time' => $checkOut,
                'no_of_days_stay' => $no_of_days,
                'no_of_guest' => $validated['no_of_guest'],

                'entrance_fee_per_pax' => $entrance_fee_per_pax,

                'accept_term_date' => now(),
                'accommodation_id' => $validated['accommodation_id'],

                'accommodation_price' => $accommodation_price,

                'discount_applied' => $accommodation->discount ?? null,

                'total_entrance_fee' => $total_entrance_fee,

                'total_price' => $total_price,

                'remaining_balance' => $total_price,

                'valid_id' => $validIdPath,
                'pets' => json_encode($pets) ?? null,
                'sheet_color' => $validated['sheet_color'] ?? null,
            ]);
            
            $admins = User::where('usertype', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new NewReservationNotification($reservation));
            }

            DB::commit();

            return redirect()
                ->route('user.reservation')
                ->with('success', 'Reservation submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            # dd($e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'An error occurred while submitting your review. Please try again later.');
        }
    }
}
