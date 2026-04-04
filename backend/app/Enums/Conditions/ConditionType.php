<?php

declare(strict_types=1);

namespace App\Enums\Conditions;

enum ConditionType: int
{
    case STATUS_CONDITION = 1;
    case DAMAGE_TYPE = 2;

    public function toString(): string
    {
        return match ($this) {
            self::STATUS_CONDITION => 'status condition',
            self::DAMAGE_TYPE => 'damage type',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'c', 'condition', 'status', 'status condition' => self::STATUS_CONDITION,
            'd', 'damage', 'damage type' => self::DAMAGE_TYPE,
            default => null,
        };
    }
}
