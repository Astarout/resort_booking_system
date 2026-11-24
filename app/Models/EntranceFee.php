<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntranceFee extends Model
{
    protected $table = 'entrance_fees';

    protected $fillable = [
        'tour_type',
        'amount',
    ];
}
