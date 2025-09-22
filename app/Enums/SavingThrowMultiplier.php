<?php

declare(strict_types=1);

namespace App\Enums;

enum SavingThrowMultiplier: int
{
    case NEGATES = 0;
    case HALF = 50;

    public function toString(): string
    {
        return match ($this) {
            self::NEGATES => 'negates',
            self::HALF => 'half',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtoupper($value)) {
            'NEGATES' => self::NEGATES,
            'HALF' => self::HALF,
            default => null,
        };
    }
}
