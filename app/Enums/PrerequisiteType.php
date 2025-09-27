<?php

declare(strict_types=1);

namespace App\Enums;

enum PrerequisiteType: int
{
    case MINIMUM_LEVEL = 1;
    case PATRON_DEITY = 2;
    case ALIGNMENT = 3;
    case MINIMUM_BASE_ATTACK_BONUS = 4;
    case FEAT = 5;
    case CHARACTER_CLASS = 6;
    case SPELLCASTER_TYPE = 7;
    case SPECIES = 8;

    public function toString(): string
    {
        return match ($this) {
            self::MINIMUM_LEVEL => 'minimum level',
            self::PATRON_DEITY => 'patron deity',
            self::ALIGNMENT => 'alignment',
            self::MINIMUM_BASE_ATTACK_BONUS => 'minimum base attack bonus',
            self::FEAT => 'feat',
            self::CHARACTER_CLASS => 'class',
            self::SPELLCASTER_TYPE => 'spellcaster type',
            self::SPECIES => 'species',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'minimum level' => self::MINIMUM_LEVEL,
            'patron deity' => self::PATRON_DEITY,
            'alignment' => self::ALIGNMENT,
            'minimum base attack bonus' => self::MINIMUM_BASE_ATTACK_BONUS,
            'feat' => self::FEAT,
            'class' => self::CHARACTER_CLASS,
            'spellcaster type' => self::SPELLCASTER_TYPE,
            'species' => self::SPECIES,
            default => null,
        };
    }
}
