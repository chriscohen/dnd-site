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
        return match ($this) {
            self::OFFICIAL => 'official',
            self::THIRD_PARTY => 'third party',
            self::HOMEBREW => 'homebrew',
        };
    }

    public static function tryFromString(string $value): ?PublicationType
    {
        return match (str_replace("_", " ", mb_strtolower($value))) {
            'official' => self::OFFICIAL,
            'third party', '3rd party' => self::THIRD_PARTY,
            'homebrew' => self::HOMEBREW,
            default => null,
        };
    }
}
