<?php

declare(strict_types=1);

namespace App\Enums;

enum DamageType: string
{
    case BLUDGEONING = 'bludgeoning';
    case PIERCING = 'piercing';
    case SLASHING = 'slashing';

    case ACID = 'acid';
    case COLD = 'cold';
    case FIRE = 'fire';
    case FORCE = 'force';
    case LIGHTNING = 'lightning';
    case NECROTIC = 'necrotic';
    case POISON = 'poison';
    case PSYCHIC = 'psychic';
    case RADIANT = 'radiant';
    case THUNDER = 'thunder';
}
