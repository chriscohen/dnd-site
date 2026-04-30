<?php

declare(strict_types=1);

namespace App\Enums\Damage;

enum DamageSourceType: int
{
    case NORMAL = 1;
    case MAGIC = 2;
    case SILVER = 3;
    case ADAMANTINE = 4;

    public function toString(): string
    {
        return match ($this) {
            self::NORMAL => 'normal',
            self::MAGIC => 'magic',
            self::SILVER => 'silver',
            self::ADAMANTINE => 'adamantine',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'normal', 'standard', 'n' => self::NORMAL,
            'magic', 'magical', 'm' => self::MAGIC,
            'silver', 'silvered', 's' => self::SILVER,
            'adamantine', 'a' => self::ADAMANTINE,
            default => null,
        };
    }
}
