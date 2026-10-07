<?php

namespace App\Services\Notifications;

use App\Models\AdminUser;
use App\Models\MarketplaceNotification;
use App\Models\MarketplaceNotificationRecipient;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MarketplaceNotificationService
{
    public function createForUsers(iterable $users, array $payload): MarketplaceNotification
    {
        $users = $users instanceof EloquentCollection || $users instanceof Collection
            ? $users->filter(fn ($user) => $user instanceof User)->values()
            : collect($users)->filter(fn ($user) => $user instanceof User)->values();

        return DB::transaction(function () use ($users, $payload) {
            $notification = MarketplaceNotification::create([
                'type' => $payload['type'] ?? 'announcement',
                'title' => $payload['title'],
                'message' => $payload['message'],
                'audience' => $payload['audience'] ?? 'selected_users',
                'action_url' => $payload['action_url'] ?? null,
                'meta' => $payload['meta'] ?? null,
                'created_by_admin_id' => $payload['created_by_admin_id'] ?? null,
            ]);

            $timestamp = now();

            $rows = $users->map(fn (User $user) => [
                'marketplace_notification_id' => $notification->id,
                'user_id' => $user->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])->all();

            if (!empty($rows)) {
                MarketplaceNotificationRecipient::insert($rows);
            }

            return $notification;
        });
    }

    public function createAnnouncementForAllSellers(
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?AdminUser $admin = null
    ): MarketplaceNotification {
        $sellers = User::query()
            ->where('seller_status', '!=', User::SELLER_STATUS_NONE)
            ->get();

        return $this->createForUsers($sellers, [
            'type' => 'announcement',
            'title' => $title,
            'message' => $message,
            'audience' => 'all_sellers',
            'action_url' => $actionUrl,
            'created_by_admin_id' => $admin?->id,
        ]);
    }

    public function notifySellerApproved(User $seller): MarketplaceNotification
    {
        return $this->createForUsers([$seller], [
            'type' => 'seller_approved',
            'title' => 'Seller profile approved',
            'message' => 'Your seller profile has been approved. You can now manage your public supplier presence.',
            'audience' => 'selected_users',
            'action_url' => route('user.dashboard'),
            'meta' => [
                'seller_user_id' => $seller->id,
            ],
        ]);
    }

    public function notifyProductApproved(Product $product): MarketplaceNotification
    {
        $product->loadMissing('user');

        return $this->createForUsers([$product->user], [
            'type' => 'product_approved',
            'title' => 'Product approved',
            'message' => sprintf('"%s" has been approved and is now live on the marketplace.', $product->name),
            'audience' => 'selected_users',
            'action_url' => route('user.products.index', ['status' => Product::STATUS_LIVE]),
            'meta' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
            ],
        ]);
    }
}
