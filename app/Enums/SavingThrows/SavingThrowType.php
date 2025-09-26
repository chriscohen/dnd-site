<?php

declare(strict_types=1);

namespace App\Enums\SavingThrows;

enum SavingThrowType: int
{
    case FORTITUDE = 1;
    case REFLEX = 2;
    case WILLPOWER = 3;
    case AC = 4;

    public function toString(): string
    {
        return match ($this) {
            self::FORTITUDE => 'fortitude',
            self::REFLEX => 'reflex',
            self::WILLPOWER => 'willpower',
            self::AC => 'AC',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower(mb_strtolower($value))) {
            'fortitude' => self::FORTITUDE,
            'reflex' => self::REFLEX,
            'willpower' => self::WILLPOWER,
            'ac' => self::AC,
            default => null,
        };
    }
}
