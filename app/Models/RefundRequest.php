<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RefundRequest extends Model
{
    protected $table = 'refund_requests';

    protected $fillable = [
        'refund_id',
        'reservation_id',
        'refund_amount',
        'mode',
        'gcash_number',
        'screen_shot',
        'remarks',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->refund_id)) {
                $model->refund_id = 'RF-' . Str::random(10);
            }
        });
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
