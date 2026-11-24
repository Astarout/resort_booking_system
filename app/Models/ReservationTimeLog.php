<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReservationTimeLog extends Model
{
    protected $table = 'reservation_time_logs';

    protected $fillable = [
        'log_id',
        'reservation_id',
        'date_and_time_in',
        'date_and_time_out',
        'status',
        'additional_fee',
        'is_paid',
        'log_by',
    ];

    protected $casts = [
        'date_and_time_in' => 'datetime',
        'date_and_time_out' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->log_id)) {
                $model->log_id = 'LOG-' . strtoupper(Str::random(8));
            }
        });
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'log_by');
    }
}
