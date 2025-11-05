<?php

namespace App\Enums;

enum AssetCriticalityLevelEnum: string
{
    case NONE = 'None';
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';
    case CRITICAL = 'Critical';

    public function getWeight(): int
    {
        return match ($this) {
            self::NONE => 0,
            self::LOW => 1,
            self::MEDIUM => 3,
            self::HIGH => 5,
            self::CRITICAL => 10,
        };
    }
}
