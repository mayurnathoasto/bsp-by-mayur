<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'audience',
        'action_url',
        'meta',
        'created_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function recipients()
    {
        return $this->hasMany(MarketplaceNotificationRecipient::class);
    }

    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }
}
