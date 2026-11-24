<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Models\Accommodation;

class CalendarController extends Controller
{
    public function index() {
        
        $accommodations = Accommodation::all();
        
        $reservations = Reservation::with(['accommodation', 'user'])
            ->whereIn('status', ['APPROVED', 'DONE'])
            ->get();

        $colors = [
            "rgba(200, 50, 80, 0.9)", "rgba(25, 105, 185, 0.9)", "rgba(220, 170, 40, 0.9)",
            "rgba(110, 70, 220, 0.9)", "rgba(210, 100, 20, 0.9)", "rgba(40, 150, 150, 0.9)",
            "rgba(200, 70, 140, 0.9)", "rgba(100, 150, 50, 0.9)", "rgba(220, 160, 20, 0.9)",
            "rgba(70, 90, 100, 0.9)", "rgba(190, 30, 80, 0.9)", "rgba(0, 150, 180, 0.9)",
            "rgba(170, 180, 40, 0.9)", "rgba(90, 60, 50, 0.9)", "rgba(50, 60, 150, 0.9)"
        ];

        $accommodationColors = [];
        $colorIndex = 0;

        foreach ($accommodations as $accommodation) {

            $accommodationName = $accommodation->name;
            if (!empty($accommodation->tour_type)) {
                $accommodationName .= ' - ' . ucfirst($accommodation->tour_type);
            }

            $accommodationColors[$accommodationName] = $colors[$colorIndex % count($colors)];
            $colorIndex++;
        }

        $events = $reservations->map(function ($reservation) use ($accommodationColors) {

            $accommodation = $reservation->accommodation;
            $accommodationName = $accommodation->name ?? 'N/A';

            if (!empty($accommodation->tour_type)) {
                $accommodationName .= ' - ' . ucfirst($accommodation->tour_type);
            }

            $color = $accommodationColors[$accommodationName] ?? 'rgba(0,0,0,0.8)';

            return [
                'id' => $reservation->id,
                'title' => ($reservation->user->first_name ?? '') . ' ' . ($reservation->user->last_name ?? 'Guest'),
                'start' => Carbon::parse($reservation->check_in_date_time)->format('Y-m-d H:i'),
                'end' => Carbon::parse($reservation->check_out_date_time)->format('Y-m-d H:i'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#fff',
                'extendedProps' => [
                    'accommodation' => $accommodationName,
                    'no_of_guest' => $reservation->no_of_guest,
                    'total_price' => $reservation->total_price,
                    'user_profile' => $reservation->user->avatar ?? 'N/A',
                ],
            ];
        });

        return view('admin.calendar', [
            'events' => $events,
            'accommodationColors' => $accommodationColors,
        ]);
    }
}
