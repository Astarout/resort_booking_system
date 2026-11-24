<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accommodation extends Model
{
    protected $table = "accommodations";
    public $timestamps = true;

    protected $fillable = [
        'name',
        'pax',
        'check_in_time',
        'check_out_time',
        'room_count',
        'image',
        'tour_type',
        'price',
        'discount',
        'description',
    ];

    public function gallery(): HasMany
    {
        return $this->hasMany(AccommodationGallery::class);
    }
}
