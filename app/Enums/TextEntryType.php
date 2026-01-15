<?php

declare(strict_types=1);

namespace App\Enums;

enum TextEntryType: int
{
    case TABLE = 1;
    case SECTION = 2;
    case LIST = 3;
    case ENTRIES = 4;
    case TEXT = 5;
    case QUOTE = 6;
    case INSET = 7;

    public function toString(): string
    {
        return match ($this) {
            self::TABLE => 'table',
            self::SECTION => 'section',
            self::LIST => 'list',
            self::ENTRIES => 'entries',
            self::TEXT => 'text',
            self::QUOTE => 'quote',
            self::INSET => 'inset',
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
            'text' => self::TEXT,
            'quote' => self::QUOTE,
            'inset' => self::INSET,
            default => null
        };
    }
}
