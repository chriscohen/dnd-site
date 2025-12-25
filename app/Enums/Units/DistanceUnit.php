<?php

namespace App\Enums\Units;

enum DistanceUnit: int
{
    case INCH = 1;
    case FOOT = 2;
    case YARD = 3;
    case METER = 4;
    case MILE = 5;

    public function plural(): string
    {
        return match ($this) {
            self::INCH => 'inches',
            self::FOOT => 'feet',
            self::YARD => 'yards',
            self::METER => 'meters',
            self::MILE => 'miles',
        };
    }

    public function getPerMeter(): float
    {
        return match ($this) {
            self::INCH => 39.3700799999,
            self::FOOT => 3.28084,
            self::YARD => 1.0936133333,
            self::METER => 1.0,
            self::MILE => 0.0006213712,
        };
    }

    public function shortName(): string
    {
        return match ($this) {
            self::INCH => 'in',
            self::FOOT => 'ft',
            self::YARD => 'yd',
            self::METER => 'm',
            self::MILE => 'mi',
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::INCH => 'in',
            self::FOOT => 'ft',
            self::YARD => 'yd',
            self::METER => 'm',
            self::MILE => 'mi',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match ($value) {
            'in', 'in.', 'inch', 'inches' => self::INCH,
            'ft', 'ft.', 'foot', 'feet' => self::FOOT,
            'yd', 'yd.', 'yds', 'yard', 'yards' => self::YARD,
            'm', 'm.', 'meter', 'metre', 'meters', 'metres' => self::METER,
            'mi', 'mi.', 'mis', 'mile', 'miles' => self::MILE,
            default => null,
        };
    }
}
