<?php

namespace App\Factories;

use App\Abstractions\NotificationExecuteInterface;
use App\Enums\NotificationChannelEnum;
use App\Factories\Entities\EmailNotification;
use App\Factories\Entities\FakeNotification;
use App\Factories\Entities\SmsNotification;
use App\Models\Notification;

class NotificationFactory
{
    public function __construct(private readonly Notification $notification){

    }

    public function create(): NotificationExecuteInterface
    {
        return match ($this->notification->channel){
            NotificationChannelEnum::EMAIL->value => app(EmailNotification::class, ['notification'=> $this->notification]),
            NotificationChannelEnum::SMS->value => app(SmsNotification::class, ['notification'=> $this->notification]),
            default => app(FakeNotification::class,  ['notification'=> $this->notification]),
        };
    }
}
