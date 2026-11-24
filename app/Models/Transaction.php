<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'transaction_id',
        'reservation_id',
        'payment_method',
        'amount',
        'change_amount',
        'payment_status',
        'recorded_by',
        'remarks',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_id)) {
                $transaction->transaction_id = 'TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
