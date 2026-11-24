<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class AdminDashboard extends Controller
{
    public function index()
    {
        $reservationStatsPerMonth = Reservation::getMonthlyReservationStatsPerAccommodation();

        $monthly_sales = Reservation::getMonthlySales();

        # dd($monthly_sales, $reservationStatsPerMonth);

        return view('admin.dashboard', [
            'monthly_sales' => $monthly_sales,
            'reservationStatsPerMonth' => $reservationStatsPerMonth,
        ]);
    }
}
