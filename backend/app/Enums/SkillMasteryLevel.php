<?php

declare(strict_types=1);

namespace App\Enums;

enum SkillMasteryLevel: int
{
    case PROFICIENT = 1;
    case EXPERTISE = 2;

    public function toString(): string
    {
        return match ($this) {
            self::PROFICIENT => 'mastery',
            self::EXPERTISE => 'expertise',
        };
    }

    public function getBonusMultiplier(): int
    {
        return match ($this) {
            self::PROFICIENT => 1,
            self::EXPERTISE => 2,
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'mastery' => self::PROFICIENT,
            'expertise' => self::EXPERTISE,
            default => null,
        };
    }
}
