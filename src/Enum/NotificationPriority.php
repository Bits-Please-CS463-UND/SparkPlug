<?php
declare(strict_types=1);

namespace App\Enum;

enum NotificationPriority: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';
    case INFO = 'info';

    public static function fromString(string $value): self
    {
        return match ($value) {
            'high' => self::HIGH,
            'medium' => self::MEDIUM,
            'low' => self::LOW,
            'info' => self::INFO,
            default => throw new \InvalidArgumentException('Unknown notification priority')
        };
    }
}
