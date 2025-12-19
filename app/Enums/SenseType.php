<?php

declare(strict_types=1);

namespace App\Enums;

enum SenseType: int
{
    case BLINDSENSE = 1;
    case BLINDSIGHT = 2;
    case DARKVISION = 3;
    case GREENSIGHT = 4;
    case INFRAVISION = 5;
    case LOW_LIGHT_VISION = 6;
    case MINDSIGHT = 7;
    case SCENT = 8;
    case TOUCHSIGHT = 9;
    case TREMORSENSE = 10;
    case TRUESIGHT = 11;
    case ULTRAVISION = 12;
    case XRAY_VISION = 13;

    public function toString(): string
    {
        return match ($this) {
            self::BLINDSENSE => 'blindsense',
            self::BLINDSIGHT => 'blindsight',
            self::DARKVISION => 'darkvision',
            self::GREENSIGHT => 'greensight',
            self::INFRAVISION => 'infravision',
            self::LOW_LIGHT_VISION => 'low light vision',
            self::MINDSIGHT => 'mindsight',
            self::SCENT => 'scent',
            self::TOUCHSIGHT => 'touchsight',
            self::TREMORSENSE => 'tremorsense',
            self::TRUESIGHT => 'truesight',
            self::ULTRAVISION => 'ultravision',
            self::XRAY_VISION => 'xray vision',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace('_', '', mb_strtolower($value))) {
            'blindsense' => self::BLINDSENSE,
            'blindsight' => self::BLINDSIGHT,
            'darkvision' => self::DARKVISION,
            'greensight' => self::GREENSIGHT,
            'infravision' => self::INFRAVISION,
            'lowlightvision', 'low light vision' => self::LOW_LIGHT_VISION,
            'mindsight' => self::MINDSIGHT,
            'scent' => self::SCENT,
            'touchsight' => self::TOUCHSIGHT,
            'tremorsense' => self::TREMORSENSE,
            'truesight' => self::TRUESIGHT,
            'ultravision' => self::ULTRAVISION,
            'xray', 'xrayvision', 'xray vision' => self::XRAY_VISION,
            default => null,
        };
    }
}
