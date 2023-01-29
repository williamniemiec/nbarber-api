<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberPhoto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'url',
        'id_barber'
    ];
}
