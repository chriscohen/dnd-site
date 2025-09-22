<?php

declare(strict_types=1);

namespace App\Enums;

enum SavingThrowType: int
{
    case FORTITUDE = 1;
    case REFLEX = 2;
    case WILLPOWER = 3;

    public function toString(): string
    {
        return match ($this) {
            self::FORTITUDE => 'fortitude',
            self::REFLEX => 'reflex',
            self::WILLPOWER => 'willpower',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtoupper($value)) {
            'FORTITUDE' => self::FORTITUDE,
            'REFLEX' => self::REFLEX,
            'WILLPOWER' => self::WILLPOWER,
            default => null,
        };
    }
}
