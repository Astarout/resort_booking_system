<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'rate',
        'comment',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
