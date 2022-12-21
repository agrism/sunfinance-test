<?php

namespace App\Factories\Entities;

use App\Abstractions\NotificationExecuteInterface;
use App\Models\Notification;

class FakeNotification implements NotificationExecuteInterface
{
    public function __construct(private readonly Notification $notification)
    {
    }

    public function execute(): void
    {

    }
}
