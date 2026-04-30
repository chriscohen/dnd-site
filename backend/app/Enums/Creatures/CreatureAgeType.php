<?php

declare(strict_types=1);

namespace App\Enums\Creatures;

enum CreatureAgeType: int
{
    case WYRMLING = 1;
    case VERY_YOUNG = 2;
    case YOUNG = 3;
    case JUVENILE = 4;
    case YOUNG_ADULT = 5;
    case ADULT = 6;
    case MATURE_ADULT = 7;
    case OLD = 8;
    case VERY_OLD = 9;
    case ANCIENT = 10;
    case WYRM = 11;
    case GREAT_WYRM = 12;
    case MAXIMUM = 13;

    public function toString(): string
    {
        return match ($this) {
            self::WYRMLING => 'wyrm',
            self::VERY_YOUNG => 'very young',
            self::YOUNG => 'young',
            self::JUVENILE => 'juvenile',
            self::YOUNG_ADULT => 'young adult',
            self::ADULT => 'adult',
            self::MATURE_ADULT => 'mature adult',
            self::OLD => 'old',
            self::VERY_OLD => 'very old',
            self::ANCIENT => 'ancient',
            self::WYRM => 'wyrm',
            self::GREAT_WYRM => 'great wyrm',
            self::MAXIMUM => 'maximum',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace('_', ' ', mb_strtolower($value))) {
            'wyrmling' => self::WYRMLING,
            'very young' => self::VERY_YOUNG,
            'young' => self::YOUNG,
            'juvenile' => self::JUVENILE,
            'mature', 'young adult' => self::YOUNG_ADULT,
            'adult' => self::ADULT,
            'mature adult' => self::MATURE_ADULT,
            'old' => self::OLD,
            'very old' => self::VERY_OLD,
            'ancient' => self::ANCIENT,
            'wyrm' => self::WYRM,
            'great wyrm' => self::GREAT_WYRM,
            'max', 'maximum' => self::MAXIMUM,
            default => null,
        };
    }
}
