<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface
{
    public function find(int $id): ?Notification;
    public function createNotification(array $attributes): Notification;
    public function createNotifications(array $notifications): array;
    public function paginate(int $perPage, ?int $page = null, ?int $filterByClientId = null): LengthAwarePaginator;
    public function get(array $ids): Collection;
}
