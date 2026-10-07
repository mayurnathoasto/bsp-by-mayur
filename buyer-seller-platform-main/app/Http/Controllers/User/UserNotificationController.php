<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceNotificationRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notificationRecipients()
            ->with('notification.creator:id,name,email')
            ->latest('id')
            ->paginate(15);

        return view('user.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, MarketplaceNotificationRecipient $recipient): RedirectResponse
    {
        abort_unless($recipient->user_id === $request->user()->id, 403);

        if ($recipient->read_at === null) {
            $recipient->forceFill(['read_at' => now()])->save();
        }

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()
            ->notificationRecipients()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }
}
