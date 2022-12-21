<?php

namespace App\Factories\Entities;

use App\Abstractions\NotificationExecuteInterface;
use App\Mail\NotificationEmail;
use App\Models\Notification;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class EmailNotification implements NotificationExecuteInterface
{
    public function __construct(private readonly Notification $notification)
    {
    }

    public function execute(): void
    {
        $to = new Address($this->notification->client->email, $this->notification->client->first_name);

        Mail::to($to)->send(app(NotificationEmail::class, ['notification' => $this->notification]));
    }
}
