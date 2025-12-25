<?php

declare(strict_types=1);

namespace App\Enums\Conditions;

enum ConditionInstanceType: int
{
    case STATUS_IMMUNITY = 1;
    case DAMAGE_IMMUNITY = 2;
    case DAMAGE_RESISTANCE = 3;
    case DAMAGE_VULNERABILITY = 4;

    public function toString(): string
    {
        return match ($this) {
            self::STATUS_IMMUNITY => 'status immunity',
            self::DAMAGE_IMMUNITY => 'damage immunity',
            self::DAMAGE_RESISTANCE => 'damage resistance',
            self::DAMAGE_VULNERABILITY => 'damage vulnerability',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'ci', 'condition', 'condition immune', 'status immune', 'status immunity' => self::STATUS_IMMUNITY,
            'di', 'immune', 'damage immune', 'damage immunity' => self::DAMAGE_IMMUNITY,
            'dr', 'damage resist', 'resist', 'damage resistance' => self::DAMAGE_RESISTANCE,
            'dv', 'vulnerability', 'vulnerable', 'damage vulnerability' => self::DAMAGE_VULNERABILITY,
            default => null,
        };
    }
}
