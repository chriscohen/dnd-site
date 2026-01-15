<?php

declare(strict_types=1);

namespace App\Enums\Spells;

enum SpellMaterialComponentConsumption: int
{
    case CONSUMED = 1;
    case NOT_CONSUMED = 2;
    case OPTIONAL = 3;

    public function toString(): string
    {
        return match ($this) {
            self::CONSUMED => 'consumed',
            self::NOT_CONSUMED => 'not consumed',
            self::OPTIONAL => 'optional',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match ($value) {
            'consumed' => self::CONSUMED,
            'not consumed' => self::NOT_CONSUMED,
            'optional' => self::OPTIONAL,
            default => null,
        };
    }
}
