<?php

namespace App\Enums;

enum NotificationChannelEnum: string
{
    case SMS = 'sms';
    case EMAIL = 'email';
}
