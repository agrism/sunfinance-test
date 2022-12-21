<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function find(int $id): ?Notification
    {
        return Notification::query()->where('id', $id)->first();
    }

    public function createNotification(array $attributes): Notification
    {
        return Notification::query()->create($attributes);
    }

    public function createNotifications(array $notifications): array
    {
        $ids = [];

        foreach ($notifications as $notificationAttributes) {
            $ids[] = $this->createNotification($notificationAttributes)->id;
        }

        return $ids;
    }


    public function paginate(int $perPage, ?int $page = null, ?int $filterByClientId = null): LengthAwarePaginator
    {
        $query = Notification::query();

        if ($filterByClientId) {
            $query->where('client_id', $filterByClientId);
        }

        return $query->paginate(perPage: config('app.perPage'), page: $page);
    }

    public function get(array $ids): Collection
    {
        return Notification::query()->whereId($ids)->get();
    }


}
