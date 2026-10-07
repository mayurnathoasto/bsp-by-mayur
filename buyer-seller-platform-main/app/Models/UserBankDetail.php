<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankDetail extends Model
{
    protected $fillable = [
        'user_id',
        'account_type',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'linked_mobile',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}