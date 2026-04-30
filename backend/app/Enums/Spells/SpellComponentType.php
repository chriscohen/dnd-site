<?php

declare(strict_types=1);

namespace App\Enums\Spells;

enum SpellComponentType: string
{
    case DIVINE_FOCUS = 'DF';
    case FOCUS = 'F';
    case MATERIAL = 'M';
    case SOMATIC = 'S';
    case VERBAL = 'V';

    public function toString(): string
    {
        return mb_strtolower($this->name);
    }
}
