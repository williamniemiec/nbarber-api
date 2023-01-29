<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date',
        'id_user',
        'id_barber'
    ];
}
