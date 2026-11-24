<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index() {
        $sales = 0;
        $accommodationStats = 0;

        return view('admin.report', [
            'sales' => $sales,
            'accommodationStats' => $accommodationStats,
        ]);
    }

    public function getSalesAndStats(Request $request, $filter, $date_range = null) {

        $totalSales = Reservation::getTotalSales();
        $allStats = Reservation::getReservationStatsPerAccommodation();

        $filteredSales = 0;
        $filteredStats = [];

        switch ($filter) {
            case 'weekly':
                $filteredSales = $totalSales->weekly_sales ?? 0;

                foreach ($allStats as $name => $stats) {
                    $filteredStats[] = [
                        'name' => $name,
                        'bookings' => $stats['weekly']['bookings'] ?? 0,
                        'sales' => $stats['weekly']['sales'] ?? 0,
                    ];
                }
                break;
            case 'monthly':
                $filteredSales = $totalSales->monthly_sales ?? 0;

                foreach ($allStats as $name => $stats) {
                    $filteredStats[] = [
                        'name' => $name,
                        'bookings' => $stats['monthly']['bookings'] ?? 0,
                        'sales' => $stats['monthly']['sales'] ?? 0,
                    ];
                }
                break;
            case 'yearly':
                $filteredSales = $totalSales->yearly_sales ?? 0;

                foreach ($allStats as $name => $stats) {
                    $filteredStats[] = [
                        'name' => $name,
                        'bookings' => $stats['yearly']['bookings'] ?? 0,
                        'sales' => $stats['yearly']['sales'] ?? 0,
                    ];
                }
                break;
            case 'range':
                if($date_range) {
                    $dates = explode(' - ', $date_range);
                    $startDate = $dates[0];
                    $endDate = $dates[1] ?? $startDate;

                    $start = Carbon::parse($startDate)->startOfDay();
                    $end   = Carbon::parse($endDate)->endOfDay();

                    $filteredSales = Reservation::whereIn('status', ['APPROVED', 'DONE'])
                        ->whereBetween('check_in_date_time', [$start, $end])
                        ->sum('paid_balance');

                    $statsInRange = Reservation::selectRaw('
                        accommodations.id as accommodation_id,
                        CONCAT_WS(" - ", accommodations.name, CONCAT(UPPER(LEFT(accommodations.tour_type, 1)), LOWER(SUBSTRING(accommodations.tour_type, 2)))) AS accommodation_name,
                        COUNT(*) as bookings,
                        SUM(paid_balance) as sales
                    ')
                    ->join('accommodations', 'reservations.accommodation_id', '=', 'accommodations.id')
                    ->whereIn('reservations.status', ['APPROVED', 'DONE'])
                    ->whereBetween('check_in_date_time', [$start, $end])
                    ->groupBy('accommodations.id', 'accommodations.name', 'accommodations.tour_type')
                    ->get();

                    foreach ($statsInRange as $row) {
                        $filteredStats[] = [
                            'name' => $row->accommodation_name,
                            'bookings' => (int) $row->bookings,
                            'sales' => (float) $row->sales,
                        ];
                    }
                } else {
                    $filteredSales = 0;
                }
                break;
            default:
                $filteredSales = 0;
                break;
        }

        # return response()->json([ 'total_sales' => $filteredSales, 'accommodation_stats' => $filteredStats,]);

        return view('admin.report', ['sales' => $filteredSales, 'accommodationStats' => $filteredStats,]);
    }
}
