<?php

declare(strict_types=1);

namespace App\Enums\Units;

enum TimeUnit: int
{
    case INSTANTANEOUS = 1;
    case STANDARD_ACTION = 2;
    case BONUS_ACTION = 3;
    case FREE_ACTION = 4;
    case TURN = 5;
    case ROUND = 6;
    case MINUTE = 7;
    case HOUR = 8;
    case DAY = 9;
    case PERMANENT = 10;

    public function format(?int $number): string
    {
        $plural = (!empty($number) && $number === 1) ? '' : 's';

        return empty($number) ?
            $this->toString() :
            $number . ' ' . $this->toString() . $plural;
    }

    public function toString(): string
    {
        return match ($this) {
            self::INSTANTANEOUS => 'instantaneous',
            self::STANDARD_ACTION => 'standard action',
            self::BONUS_ACTION => 'bonus action',
            self::FREE_ACTION => 'free action',
            self::TURN => 'turn',
            self::ROUND => 'round',
            self::MINUTE => 'minute',
            self::HOUR => 'hour',
            self::DAY => 'day',
            self::PERMANENT => 'permanent',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace('_', ' ', mb_strtolower($value))) {
            'instant', 'instantaneous', 'immediate' => self::INSTANTANEOUS,
            'action', 'standard action' => self::STANDARD_ACTION,
            'bonus', 'bonus action' => self::BONUS_ACTION,
            'free', 'free action' => self::FREE_ACTION,
            'turn' => self::TURN,
            'round', 'rd', 'rds' => self::ROUND,
            'minute', 'minutes', 'min' => self::MINUTE,
            'hour', 'h', 'hr', 'hrs' => self::HOUR,
            'day', 'd' => self::DAY,
            'permanent', 'perm' => self::PERMANENT,
            default => null,
        };
    }
}
