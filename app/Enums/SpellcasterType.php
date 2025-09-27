<?php

declare(strict_types=1);

namespace App\Enums;

enum SpellcasterType: int
{
    case ARCANE = 1;
    case DIVINE = 2;
    case ANY = 3;

    public function toString(): string
    {
        return match ($this) {
            self::ARCANE => 'arcane',
            self::DIVINE => 'divine',
            self::ANY => 'any',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'arcane' => self::ARCANE,
            'divine' => self::DIVINE,
            'any' => self::ANY,
            default => null,
        };
    }
}
