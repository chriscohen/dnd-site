<?php

declare(strict_types=1);

namespace App\Enums\Spells;

enum SpellType4e: int
{
    case ATTACK = 1;
    case UTILITY = 2;
    case CANTRIP = 3;
    case FEATURE = 4;

    public function toString(): string
    {
        return match ($this) {
            self::ATTACK => 'attack',
            self::UTILITY => 'utility',
            self::CANTRIP => 'candidate',
            self::FEATURE => 'feature',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match ($value) {
            'attack' => self::ATTACK,
            'utility' => self::UTILITY,
            'candidate' => self::CANTRIP,
            'feature' => self::FEATURE,
            default => null,
        };
    }
}
