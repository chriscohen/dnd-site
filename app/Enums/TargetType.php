<?php

namespace App\Enums;

enum TargetType: int
{
    case CREATURE = 1;
    case OBJECT = 2;
    case CREATURE_OR_OBJECT = 3;
    case SELF = 4;
    case ALLY = 5;
    case ENEMY = 6;
    case AREA = 7;

    public function toString(): string
    {
        return match ($this) {
            TargetType::CREATURE => 'creature',
            TargetType::OBJECT => 'object',
            TargetType::CREATURE_OR_OBJECT => 'creature or object',
            TargetType::SELF => 'self',
            TargetType::ALLY => 'ally',
            TargetType::ENEMY => 'enemy',
            TargetType::AREA => 'area',
        };
    }

    public function tryFromString(string $value): ?self
    {
        return match (str_replace(' ', '_', mb_strtolower($value))) {
            'creature' => self::CREATURE,
            'object' => self::OBJECT,
            'creature_or_object' => self::CREATURE_OR_OBJECT,
            'self' => self::SELF,
            'ally' => self::ALLY,
            'enemy' => self::ENEMY,
            'area' => self::AREA,
            default => null,
        };
    }
}
