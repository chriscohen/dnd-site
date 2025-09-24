<?php

namespace App\Enums\Spells;

/**
 * In 4th edition, how frequently the spell can be used.
 */
enum SpellFrequency: int
{
    case AT_WILL = 1;
    case ENCOUNTER = 2;
    case DAILY = 3;

    public function toString(): string
    {
        return match ($this) {
            self::AT_WILL => 'at-will',
            self::ENCOUNTER => 'encounter',
            self::DAILY => 'daily',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace('-', '_', mb_strtolower($value))) {
            'at_will' => self::AT_WILL,
            'encounter' => self::ENCOUNTER,
            'daily' => self::DAILY,
            default => null,
        };
    }
}
