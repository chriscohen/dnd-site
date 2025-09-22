<?php

declare(strict_types=1);

namespace App\Enums;

enum PerLevelMode: int
{
    case NONE = 0;
    case PER_LEVEL = 1;
    case PER_CASTER_LEVEL = 2;

    public function toString(): string
    {
        return match ($this) {
            self::NONE => 'none',
            self::PER_LEVEL => 'per level',
            self::PER_CASTER_LEVEL => 'per caster level',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'none' => self::NONE,
            'level' => self::PER_LEVEL,
            'caster' => self::PER_CASTER_LEVEL,
            default => null
        };
    }
}
