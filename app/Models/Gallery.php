<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = "galleries";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'image',
        'type',
        'description',
        'created_at',
        'updated_at',
    ];
}
