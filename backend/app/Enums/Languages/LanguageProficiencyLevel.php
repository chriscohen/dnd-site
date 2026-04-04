<?php

declare(strict_types=1);

namespace App\Enums\Languages;

enum LanguageProficiencyLevel: int
{
    case NATIVE = 1;
    case FLUENT = 2;
    case EXPERT = 3;
    case INTERMEDIATE = 4;
    case BASIC = 5;

    public function toString(): string
    {
        return match ($this) {
            self::NATIVE => 'native',
            self::FLUENT => 'fluent',
            self::EXPERT => 'expert',
            self::INTERMEDIATE => 'intermediate',
            self::BASIC => 'basic',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'native' => self::NATIVE,
            'fluent' => self::FLUENT,
            'expert' => self::EXPERT,
            'intermediate' => self::INTERMEDIATE,
            'basic' => self::BASIC,
            default => null,
        };
    }
}
