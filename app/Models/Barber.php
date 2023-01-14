<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'avatar',
        'stars',
        'latitude',
        'longitude'
    ];
}
