<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceNotificationRecipient extends Model
{
    protected $fillable = [
        'marketplace_notification_id',
        'user_id',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function notification()
    {
        return $this->belongsTo(MarketplaceNotification::class, 'marketplace_notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
