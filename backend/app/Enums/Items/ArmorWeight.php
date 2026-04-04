<?php

declare(strict_types=1);

namespace App\Enums\Items;

enum ArmorWeight: int
{
    case LIGHT = 1;
    case MEDIUM = 2;
    case HEAVY = 3;
    case SHIELD = 4;

    public function toString(): string
    {
        return match ($this) {
            self::LIGHT => 'light',
            self::MEDIUM => 'medium',
            self::HEAVY => 'heavy',
            self::SHIELD => 'shield',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'l', 'light' => self::LIGHT,
            'm', 'medium' => self::MEDIUM,
            'h', 'heavy' => self::HEAVY,
            's', 'shield' => self::SHIELD,
            default => null,
        };
    }
}
