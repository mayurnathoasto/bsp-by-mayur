<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApiToken extends Model
{
    protected $fillable = [
        'admin_user_id','token_hash','expires_at','revoked_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class);
    }
}
