<?php

declare(strict_types=1);

namespace App\Enums\Effects;

enum EffectType: int
{
    case DAMAGE = 1;
    case HEALING = 2;

    public function toString(): string
    {
        return match ($this) {
            self::DAMAGE => 'Damage',
            self::HEALING => 'Healing',
        };
    }
    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'damage' => self::DAMAGE,
            'healing' => self::HEALING,
            default => null,
        };
    }
}
