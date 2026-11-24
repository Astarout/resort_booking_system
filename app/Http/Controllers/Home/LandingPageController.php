<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Gallery;
use App\Models\Accommodation;
use App\Models\Reservation;
use App\Models\AccommodationGallery;
use App\Models\EntranceFee;

class LandingPageController extends Controller
{

    public function index()
    {
        $ratings = Rating::where('status', '=', 'visible')->latest()->get();
        $all_ratings = Rating::all();
        $galleries = Gallery::all();
        $accommodations = Accommodation::all();

        foreach ($accommodations as $accommodation) {
            $accommodation->disabled_dates = Reservation::getFullyBookedDates($accommodation->id);
        }

        # dd($accommodations);

        $averageRating = $all_ratings->count() > 0
            ? round($all_ratings->avg('rate'), 1)
            : 0;

        return view('welcome', [
            'ratings' => $ratings,
            'averageRating' => $averageRating,
            'galleries' => $galleries,
            'accommodations' => $accommodations,
        ]);
    }

    public function TermsAndCondition() {
        return view('home.terms-and-conditions');
    }

    public function Guidelines() {
        return view('home.guidelines');
    }

    public function RefundAndRebookingPolicy() {
        return view('home.refund-and-rebooking-policy');
    }

    public function VillaBookingReminders() {
        return view('home.villa-booking-reminders');
    }

    public function viewAccommodationDetails($encrypted_id) {
        try {
            $id = decrypt($encrypted_id);
        } catch(\Exception $e) {
            abort(404, 'Invalid accommodation ID.');
        }

        $accommodation = Accommodation::findOrFail($id);

        $entranceFee = EntranceFee::where('tour_type', $accommodation->tour_type)->first();

        $entrance_fee_per_pax = $entranceFee ? $entranceFee->amount : 0;

        $gallery = AccommodationGallery::where('accommodation_id', $id)->get();

        $fullyBookedDates = Reservation::getFullyBookedDates($accommodation->id);

        $accommodation->disabled_dates = $fullyBookedDates;

        return view('home.view-accommodation', [
            'accommodation' => $accommodation,
            'gallery' => $gallery,
            'entrance_fee_per_pax' => $entrance_fee_per_pax,
        ]);
    }
}
