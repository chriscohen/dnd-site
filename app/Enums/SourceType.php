<?php

declare(strict_types=1);

namespace App\Enums;

enum SourceType: int
{
    case SOURCEBOOK = 1;
    case NOVEL = 2;
    case WEBSITE = 3;
    case MAGAZINE = 4;

    public function toString(): string
    {
        return mb_ucfirst(mb_strtolower($this->name));
    }

    public static function tryFromString(string $value): ?SourceType
    {
        return match (mb_strtoupper($value)) {
            'SOURCEBOOK' => self::SOURCEBOOK,
            'NOVEL' => self::NOVEL,
            'WEBSITE' => self::WEBSITE,
            'MAGAZINE' => self::MAGAZINE,
            default => null,
        };
    }
}
