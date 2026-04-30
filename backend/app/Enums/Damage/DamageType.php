<?php

declare(strict_types=1);

namespace App\Enums\Damage;

enum DamageType: int
{
    case BLUDGEONING = 1;
    case PIERCING = 2;
    case SLASHING = 3;

    case ACID = 4;
    case COLD = 5;
    case FIRE = 6;
    case FORCE = 7;
    case LIGHTNING = 8;
    case NECROTIC = 9;
    case POISON = 10;
    case PSYCHIC = 11;
    case RADIANT = 12;
    case THUNDER = 13;

    public function toString(): string
    {
        return match ($this) {
            self::BLUDGEONING => 'bludgeoning',
            self::PIERCING => 'piercing',
            self::SLASHING => 'slashing',
            self::ACID => 'acid',
            self::COLD => 'cold',
            self::FIRE => 'fire',
            self::FORCE => 'force',
            self::LIGHTNING => 'lightning',
            self::NECROTIC => 'necrotic',
            self::POISON => 'poison',
            self::PSYCHIC => 'psychic',
            self::RADIANT => 'radiant',
            self::THUNDER => 'thunder',
        };
    }

    public static function tryFromString(string $value): ?self
    {
        return match (mb_strtolower($value)) {
            'bludgeoning' => self::BLUDGEONING,
            'piercing' => self::PIERCING,
            'slashing' => self::SLASHING,
            'acid' => self::ACID,
            'cold' => self::COLD,
            'fire' => self::FIRE,
            'force' => self::FORCE,
            'lightning' => self::LIGHTNING,
            'necrotic' => self::NECROTIC,
            'poison' => self::POISON,
            'psychic' => self::PSYCHIC,
            'radiant' => self::RADIANT,
            'thunder' => self::THUNDER,
            default => null,
        };
    }
}
