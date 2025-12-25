<?php

declare(strict_types=1);

namespace App\Enums;

enum ScalingType: int
{
    case CANTRIP = 1;
    case EVERY_LEVEL = 2;
    case EVERY_TWO_LEVELS = 3;

    public function toString(): string
    {
        return match ($this) {
            self::CANTRIP => 'can trip',
            self::EVERY_LEVEL => 'every level',
            self::EVERY_TWO_LEVELS => 'every two levels',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'cantrip' => self::CANTRIP,
            'every level' => self::EVERY_LEVEL,
            'every two levels' => self::EVERY_TWO_LEVELS,
            default => null,
        };
    }
}
