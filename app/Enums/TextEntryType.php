<?php

declare(strict_types=1);

namespace App\Enums;

enum TextEntryType: int
{
    case TABLE = 1;
    case SECTION = 2;
    case LIST = 3;
    case ENTRIES = 4;

    public function toString(): string
    {
        return match ($this) {
            self::TABLE => 'table',
            self::SECTION => 'section',
            self::LIST => 'list',
            self::ENTRIES => 'entries',
        };
    }

    public function toStringShort(): string
    {
        return $this->toString(true);
    }

    public static function tryFromString(string $value): ?TextEntryType
    {
        return match (mb_strtolower($value)) {
            'list' => self::LIST,
            'table' => self::TABLE,
            'section' => self::SECTION,
            'entries' => self::ENTRIES,
            default => null
        };
    }
}
