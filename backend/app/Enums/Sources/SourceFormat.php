<?php

declare(strict_types=1);

namespace App\Enums\Sources;

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
        return match ($this) {
            self::PRINT => 'Print',
            self::PDF => 'PDF',
            self::ROLL_20 => 'Roll20 VTT',
            self::FOUNDRY => 'Foundry VTT',
            self::FANTASY_GROUNDS => 'Fantasy Grounds',
            self::DND_BEYOND => 'D&D Beyond',
        };
    }

    public static function tryFromString(string $value): ?SourceFormat
    {
        return match (mb_strtolower(str_replace(' ', '', $value))) {
            'print' => self::PRINT,
            'pdf' => self::PDF,
            'roll20', 'roll-20', 'roll_20' => self::ROLL_20,
            'foundry' => self::FOUNDRY,
            'fantasygrounds', 'fantasy-grounds', 'fantasy_grounds' => self::FANTASY_GROUNDS,
            'beyond', 'd&d_beyond', 'dnd_beyond', 'dndbeyond', 'd&dbeyond' => self::DND_BEYOND,
            default => null,
        };
    }
}
