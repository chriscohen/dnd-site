<?php

declare(strict_types=1);

namespace App\Enums;

enum Rarity: int
{
    case COMMON = 1;
    case UNCOMMON = 2;
    case RARE = 3;
    case VERY_RARE = 4;
    case LEGENDARY = 5;
    case ARTIFACT = 6;

    public function toString(): string
    {
        return match ($this) {
            self::COMMON => 'common',
            self::UNCOMMON => 'uncommon',
            self::RARE => 'rare',
            self::VERY_RARE => 'very rare',
            self::LEGENDARY => 'legendary',
            self::ARTIFACT => 'artifact',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'common' => self::COMMON,
            'uncommon' => self::UNCOMMON,
            'rare' => self::RARE,
            'very_rare' => self::VERY_RARE,
            'legendary' => self::LEGENDARY,
            'artifact' => self::ARTIFACT,
            default => null,
        };
    }
}
