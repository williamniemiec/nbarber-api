<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberTestimonial extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'rate',
        'body',
        'id_barber',
        'id_user'
    ];
}
