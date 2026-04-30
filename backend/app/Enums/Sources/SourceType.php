<?php

declare(strict_types=1);

namespace App\Enums\Sources;

enum SourceType: int
{
    case SOURCEBOOK = 1;
    case NOVEL = 2;
    case WEBSITE = 3;
    case MAGAZINE = 4;
    case BOXED_SET = 5;

    public function toString(): string
    {
        return match ($this) {
            self::SOURCEBOOK => 'sourcebook',
            self::NOVEL => 'novel',
            self::WEBSITE => 'website',
            self::MAGAZINE => 'magazine',
            self::BOXED_SET => 'boxed-set',
        };
    }

    public static function tryFromString(string $value): ?SourceType
    {
        return match (mb_strtoupper($value)) {
            'SOURCEBOOK' => self::SOURCEBOOK,
            'NOVEL' => self::NOVEL,
            'WEBSITE' => self::WEBSITE,
            'MAGAZINE' => self::MAGAZINE,
            'BOXED_SET' => self::BOXED_SET,
            default => null,
        };
    }
}
