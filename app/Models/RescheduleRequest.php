<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RescheduleRequest extends Model
{
    use HasFactory;

    protected $table = 'reschedule_requests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'new_check_in_date_time',
        'new_check_out_date_time',
        'reason',
        'status',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
