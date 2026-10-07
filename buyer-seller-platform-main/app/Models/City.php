<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'country_code',
        'name',
        'state',
        'pincode',
        'is_active',
    ];
}
