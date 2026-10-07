<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    public const TYPE_TICKET = 'ticket';
    public const TYPE_REPORT = 'report';

    public const STATUS_OPEN = 'open';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'user_id',
        'target_user_id',
        'type',
        'status',
        'order_reference',
        'subject',
        'message',
        'context_url',
        'admin_reply',
        'admin_replied_by',
        'admin_replied_at',
    ];

    protected function casts(): array
    {
        return [
            'admin_replied_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function adminRepliedBy()
    {
        return $this->belongsTo(AdminUser::class, 'admin_replied_by');
    }
}
