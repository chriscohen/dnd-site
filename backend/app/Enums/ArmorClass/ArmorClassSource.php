<?php

declare(strict_types=1);

namespace App\Enums\ArmorClass;

enum ArmorClassSource: int
{
    case BASE = 1;
    case NATURAL = 2;
    case EQUIPMENT = 3;

    public function toString(): string
    {
        return match ($this) {
            self::BASE => 'base',
            self::NATURAL => 'natural',
            self::EQUIPMENT => 'equipment',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'base' => self::BASE,
            'natural', 'natural armor' => self::NATURAL,
            'equipment' => self::EQUIPMENT,
            default => null,
        };
    }
}
