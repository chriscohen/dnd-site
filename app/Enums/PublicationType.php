<?php

declare(strict_types=1);

namespace App\Enums;

enum PublicationType: int
{
    case OFFICIAL = 1;
    case THIRD_PARTY = 2;
    case HOMEBREW = 3;

    public function toString(): string
    {
        return match ($this->name) {
            'OFFICIAL' => 'official',
            'THIRD_PARTY' => 'third party',
            'HOMEBREW' => 'homebrew',
        };
    }

    public static function tryFromString(string $value): ?PublicationType
    {
        return match (mb_strtoupper($value)) {
            'OFFICIAL' => self::OFFICIAL,
            'THIRD_PARTY' => self::THIRD_PARTY,
            'HOMEBREW' => self::HOMEBREW,
            default => null,
        };
    }
}
