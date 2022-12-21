<?php

namespace App\Abstractions;

use App\Models\Notification;

interface NotificationExecuteInterface
{
    public function __construct(Notification $notification);

    public function execute(): void;
}
