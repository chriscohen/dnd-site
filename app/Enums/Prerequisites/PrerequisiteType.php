<?php

declare(strict_types=1);

namespace App\Enums\Prerequisites;

use InvalidArgumentException;

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
    case SKILL = 9;
    case WEAPON_PROFICIENCY = 10;
    case SPECIAL = 11;
    case ABILITY_SCORE = 12;
    case OTHER = 99;

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
            self::SKILL => 'skill',
            self::WEAPON_PROFICIENCY => 'weapon proficiency',
            self::SPECIAL => 'special',
            self::ABILITY_SCORE => 'ability score',
            self::OTHER => 'other',
        };
    }

    public static function tryFromString(string $value, bool $throw = false): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'minimum_level' => self::MINIMUM_LEVEL,
            'patron_deity' => self::PATRON_DEITY,
            'alignment' => self::ALIGNMENT,
            'base_attack_bonus', 'minimum_base_attack_bonus' => self::MINIMUM_BASE_ATTACK_BONUS,
            'feat' => self::FEAT,
            'class' => self::CHARACTER_CLASS,
            'spellcaster_type' => self::SPELLCASTER_TYPE,
            'species' => self::SPECIES,
            'skill' => self::SKILL,
            'weapon_proficiency' => self::WEAPON_PROFICIENCY,
            'special' => self::SPECIAL,
            'ability_score' => self::ABILITY_SCORE,
            'other' => self::OTHER,
            default => $throw ?
                throw new InvalidArgumentException('"' . $value . '" is not a valid prerequisite type.') :
                null,
        };
    }
}
