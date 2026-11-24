<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Reservation extends Model
{
    protected $table = "reservations";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'reservation_date',

        'check_in_date_time', # date after desired_date
        'check_out_date_time', # date after check_in_date
        'no_of_days_stay', # unsignedsmallint after_check_out_date

        'no_of_guest',

        'entrance_fee_per_pax',

        'pets',
        'sheet_color',
        'valid_id',

        'accept_term_date',
        'accommodation_id',
        'status', # enum ('PENDING', 'APPROVED', 'CANCELLED', 'DONE', 'DECLINED')

        'accommodation_price',

        'discount_applied',

        'total_entrance_fee',

        'total_price',

        'paid_balance',

        'remaining_balance',

        'paymongo_source_id',

        'rebook_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function log()
    {
        return $this->hasOne(ReservationTimeLog::class, 'reservation_id');
    }

    public function refund_request()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function rescheduleRequests()
    {
        return $this->hasMany(RescheduleRequest::class);
    }

    public static function getFullyBookedDates($accommodation_id)
    {
        $reservations = self::where('accommodation_id', $accommodation_id)
            ->whereIn('status', ['APPROVED', 'PENDING'])
            ->get(['check_in_date_time', 'check_out_date_time']);

        $roomCount = DB::table('accommodations')
            ->where('id', $accommodation_id)
            ->value('room_count');

        $fullyBookedDates = [];
        $dateCounts = [];

        foreach ($reservations as $reservation) {
            $checkIn = Carbon::parse($reservation->check_in_date_time)->startOfDay();
            $checkOut = Carbon::parse($reservation->check_out_date_time)->startOfDay();

            // Single-day booking
            if ($checkIn->eq($checkOut)) {
                $dateKey = $checkIn->toDateString();
                if (!isset($dateCounts[$dateKey])) {
                    $dateCounts[$dateKey] = 0;
                }
                $dateCounts[$dateKey]++;
                continue;
            }

            // Multi-day booking (exclude checkout day)
            $currentDate = $checkIn;
            while ($currentDate < $checkOut) {
                $dateKey = $currentDate->toDateString();
                if (!isset($dateCounts[$dateKey])) {
                    $dateCounts[$dateKey] = 0;
                }
                $dateCounts[$dateKey]++;
                $currentDate->addDay();
            }
        }

        // Filter fully booked dates
        foreach ($dateCounts as $date => $count) {
            if ($count >= $roomCount) {
                $fullyBookedDates[] = $date;
            }
        }

        return $fullyBookedDates;
    }

    public static function getReservationStatsPerAccommodation()
    {
        $stats = self::selectRaw('
            accommodations.id as accommodation_id,
            CONCAT_WS(" - ", accommodations.name, CONCAT(UPPER(LEFT(accommodations.tour_type, 1)), LOWER(SUBSTRING(accommodations.tour_type, 2)))) AS accommodation_name,

            -- Bookings
            SUM(
                CASE
                    WHEN YEAR(reservations.check_in_date_time) = YEAR(CURDATE())
                    AND WEEK(reservations.check_in_date_time, 0) = WEEK(CURDATE(), 0)
                    THEN 1 ELSE 0
                END
            ) as weekly_bookings,

            SUM(
                CASE
                    WHEN YEAR(reservations.check_in_date_time) = YEAR(CURDATE())
                    AND MONTH(reservations.check_in_date_time) = MONTH(CURDATE())
                    THEN 1 ELSE 0
                END
            ) as monthly_bookings,

            SUM(
                CASE
                    WHEN YEAR(reservations.check_in_date_time) = YEAR(CURDATE())
                    THEN 1 ELSE 0
                END
            ) as yearly_bookings,

            -- Sales
            SUM(
                CASE
                    WHEN YEAR(reservations.check_in_date_time) = YEAR(CURDATE())
                    AND WEEK(reservations.check_in_date_time, 0) = WEEK(CURDATE(), 0)
                    THEN reservations.paid_balance ELSE 0
                END
            ) as weekly_sales,

            SUM(
                CASE
                    WHEN YEAR(reservations.check_in_date_time) = YEAR(CURDATE())
                    AND MONTH(reservations.check_in_date_time) = MONTH(CURDATE())
                    THEN reservations.paid_balance ELSE 0
                END
            ) as monthly_sales,

            SUM(
                CASE
                    WHEN YEAR(reservations.check_in_date_time) = YEAR(CURDATE())
                    THEN reservations.paid_balance ELSE 0
                END
            ) as yearly_sales
        ')
        ->join('accommodations', 'reservations.accommodation_id', '=', 'accommodations.id')
        ->whereIn('reservations.status', ['APPROVED', 'DONE'])
        ->groupBy('accommodations.id', 'accommodations.name', 'accommodations.tour_type')
        ->get();

        // Convert result into desired array structure
        $formatted = [];

        foreach ($stats as $row) {
            $formatted[$row->accommodation_name] = [
                'weekly' => [
                    'bookings' => (int) $row->weekly_bookings,
                    'sales'    => (float) $row->weekly_sales,
                ],
                'monthly' => [
                    'bookings' => (int) $row->monthly_bookings,
                    'sales'    => (float) $row->monthly_sales,
                ],
                'yearly' => [
                    'bookings' => (int) $row->yearly_bookings,
                    'sales'    => (float) $row->yearly_sales,
                ],
            ];
        }

        return $formatted;
    }

    public static function getMonthlyReservationStatsPerAccommodation()
    {
        return self::selectRaw('
            accommodations.id as accommodation_id,
            CONCAT_WS(" - ", accommodations.name, CONCAT(UPPER(LEFT(accommodations.tour_type, 1)), LOWER(SUBSTRING(accommodations.tour_type, 2)))) AS accommodation_name,
            COUNT(*) as monthly_bookings
        ')
        ->join('accommodations', 'reservations.accommodation_id', '=', 'accommodations.id')
        ->whereIn('reservations.status', ['APPROVED', 'DONE'])
        ->whereRaw('YEAR(reservations.check_in_date_time) = YEAR(CURDATE())')
        ->whereRaw('MONTH(reservations.check_in_date_time) = MONTH(CURDATE())')
        ->groupBy('accommodations.id', 'accommodations.name', 'accommodations.tour_type')
        ->get();
    }

    public static function getTotalSales() {
        return self::selectRaw('
            SUM(CASE WHEN YEARWEEK(check_in_date_time, 0) = YEARWEEK(CURDATE(), 0) THEN paid_balance ELSE 0 END) as weekly_sales,
            SUM(CASE WHEN YEAR(check_in_date_time) = YEAR(CURDATE())
                     AND MONTH(check_in_date_time) = MONTH(CURDATE()) THEN paid_balance ELSE 0 END) as monthly_sales,
            SUM(CASE WHEN YEAR(check_in_date_time) = YEAR(CURDATE()) THEN paid_balance ELSE 0 END) as yearly_sales
        ')
        ->whereIn('reservations.status', ['APPROVED', 'DONE'])
        ->first();
    }

    public static function getMonthlySales()
    {
        return self::whereIn('reservations.status', ['APPROVED', 'DONE'])
            ->whereRaw('YEAR(check_in_date_time) = YEAR(CURDATE())')
            ->whereRaw('MONTH(check_in_date_time) = MONTH(CURDATE())')
            ->sum('paid_balance');
    }
}
