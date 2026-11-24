<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccommodationGallery extends Model
{
    protected $table = "accommodation_gallery";
    public $timestamps = true;

    protected $fillable = [
        'accommodation_id',
        'image'
    ];

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }
}
