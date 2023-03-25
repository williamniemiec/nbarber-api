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

class BarberTestimonial extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'rate',
        'body',
        'id_barber',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo('user', 'id_user', 'id');
    }
}
