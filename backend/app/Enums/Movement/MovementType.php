<?php

declare(strict_types=1);

namespace App\Enums\Movement;

enum MovementType: int
{
    case BURROW = 1;
    case CLIMB = 2;
    case FLY = 3;
    case SWIM = 4;
    case WALK = 5;

    public function toString(): string
    {
        return match ($this) {
            self::BURROW => 'burrow',
            self::CLIMB => 'climb',
            self::FLY => 'fly',
            self::SWIM => 'swim',
            self::WALK => 'walk',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'burrow' => self::BURROW,
            'climb' => self::CLIMB,
            'fly' => self::FLY,
            'swim' => self::SWIM,
            'walk' => self::WALK,
            default => null,
        };
    }
}
