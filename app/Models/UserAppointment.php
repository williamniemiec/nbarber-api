<?php
/*
 * Copyright (c) William Niemiec.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'date',
        'id_user',
        'id_barber',
        'id_service'
    ];
}
