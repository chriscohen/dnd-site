<?php

declare(strict_types=1);

namespace App\Enums;

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

    public function format(int $number): string
    {
        $plural = $number === 1 ? '' : 's';

        return $number . ' ' . $this->toString() . $plural;
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
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'instantaneous' => self::INSTANTANEOUS,
            'standard_action' => self::STANDARD_ACTION,
            'bonus_action' => self::BONUS_ACTION,
            'free_action' => self::FREE_ACTION,
            'turn' => self::TURN,
            'round' => self::ROUND,
            'minute' => self::MINUTE,
            'hour' => self::HOUR,
            'day' => self::DAY,
            default => null,
        };
    }
}
