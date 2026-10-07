<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceNotification;
use App\Services\Notifications\MarketplaceNotificationService;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function __construct(
        protected MarketplaceNotificationService $notifications
    ) {
    }

    public function index(Request $request)
    {
        $type = trim((string) $request->query('type', ''));
        $search = trim((string) $request->query('search', ''));

        $query = MarketplaceNotification::query()
            ->with('creator:id,name,email')
            ->withCount([
                'recipients',
                'recipients as read_count' => fn ($builder) => $builder->whereNotNull('read_at'),
            ])
            ->latest('id');

        if ($type !== '') {
            $query->where('type', $type);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
            'action_url' => ['nullable', 'string', 'max:500'],
        ]);

        $notification = $this->notifications->createAnnouncementForAllSellers(
            $data['title'],
            $data['message'],
            $data['action_url'] ?? null,
            $request->user()
        );

        return response()->json([
            'message' => 'Notification sent successfully.',
            'notification_id' => $notification->id,
        ], 201);
    }
}
