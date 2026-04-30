<?php

declare(strict_types=1);

namespace App\Enums;

enum PerLevelMode: int
{
    case NONE = 0;
    case PER_LEVEL = 1;
    case PER_CASTER_LEVEL = 2;
    case PER_2_LEVELS = 3;
    case PER_2_CASTER_LEVELS = 4;

    public function toString(): string
    {
        return match ($this) {
            self::NONE => 'none',
            self::PER_LEVEL => 'per level',
            self::PER_CASTER_LEVEL => 'per caster level',
            self::PER_2_LEVELS => 'per 2 levels',
            self::PER_2_CASTER_LEVELS => 'per 2 caster levels',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'none' => self::NONE,
            'level', 'per level' => self::PER_LEVEL,
            'caster', 'caster level', 'per caster level' => self::PER_CASTER_LEVEL,
            '2 levels', 'per 2 levels' => self::PER_2_LEVELS,
            '2 caster levels', 'per 2 caster levels' => self::PER_2_CASTER_LEVELS,
            default => null
        };
    }
}
