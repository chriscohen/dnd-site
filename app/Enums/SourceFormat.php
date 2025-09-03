<?php

declare(strict_types=1);

namespace App\Enums;

enum SourceFormat: int
{
    case PRINT = 1;
    case PDF = 2;
    case ROLL_20 = 3;
    case FOUNDRY = 4;
    case FANTASY_GROUNDS = 5;
    case DND_BEYOND = 6;

    public function toString(): string
    {
        return mb_strtolower(str_replace('_', ' ', $this->name));
    }

    public static function tryFromString(string $value): ?SourceFormat
    {
        return match (mb_strtoupper($value)) {
            'PRINT' => self::PRINT,
            'PDF' => self::PDF,
            'ROLL_20' => self::ROLL_20,
            'FOUNDRY' => self::FOUNDRY,
            'FANTASY_GROUNDS' => self::FANTASY_GROUNDS,
            'DND_BEYOND' => self::DND_BEYOND,
            default => null,
        };
    }
}
