<?php

namespace App\Factories\Entities;

use App\Abstractions\NotificationExecuteInterface;
use App\Models\Notification;
use App\Sms\NotificationSms;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class SmsNotification implements NotificationExecuteInterface
{
    public function __construct(private readonly Notification $notification)
    {
    }
    public function execute(): void
    {
        $to = new Address($this->notification->client->email,
            sprintf( '%s %s' ,$this->notification->client->first_name, $this->notification->client->phone_number));

        Mail::to($to)
            ->send(app(NotificationSms::class, ['notification' => $this->notification]));
    }
}
