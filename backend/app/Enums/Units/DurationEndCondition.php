<?php

declare(strict_types=1);

namespace App\Enums\Units;

enum DurationEndCondition: int
{
    case DISPEL = 1;
    case TRIGGER = 2;

    public function toString(): string
    {
        return match ($this) {
            self::DISPEL => 'dispel',
            self::TRIGGER => 'trigger',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'dispel' => self::DISPEL,
            'trigger' => self::TRIGGER,
            default => null,
        };
    }
}
