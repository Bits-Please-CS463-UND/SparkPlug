<?php
declare(strict_types=1);

namespace App\Enum;

enum NotificationPriority: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';
    case INFO = 'info';
}
