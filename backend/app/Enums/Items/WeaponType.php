<?php

declare(strict_types=1);

namespace App\Enums\Items;

enum WeaponType: int
{
    case SIMPLE_MELEE = 1;
    case SIMPLE_RANGED = 2;
    case MARTIAL_MELEE = 3;
    case MARTIAL_RANGED = 4;
    case MELEE = 5;
    case RANGED = 6;
    case SIMPLE = 7;
    case MARTIAL = 8;

    public function toString(): string
    {
        return match ($this) {
            self::SIMPLE_MELEE => 'simple melee',
            self::SIMPLE_RANGED => 'simple ranged',
            self::MARTIAL_MELEE => 'martial melee',
            self::MARTIAL_RANGED => 'martial ranged',
            self::MELEE => 'melee',
            self::RANGED => 'ranged',
            self::SIMPLE => 'simple',
            self::MARTIAL => 'martial',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'simple melee' => self::SIMPLE_MELEE,
            'simple ranged' => self::SIMPLE_RANGED,
            'martial melee' => self::MARTIAL_MELEE,
            'martial ranged' => self::MARTIAL_RANGED,
            'melee' => self::MELEE,
            'ranged' => self::RANGED,
            'simple' => self::SIMPLE,
            'martial' => self::MARTIAL,
            default => null,
        };
    }
}
