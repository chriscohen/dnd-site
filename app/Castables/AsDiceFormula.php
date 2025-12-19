<?php

declare(strict_types=1);

namespace App\Castables;

use App\Models\Dice\DiceFormula;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class AsDiceFormula implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): DiceFormula
    {
        return new DiceFormula($value ?? '');
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if ($value instanceof DiceFormula) {
            return $value->__toString();
        } elseif (!is_string($value)) {
            throw new InvalidArgumentException('Invalid value for DiceFormula: ' . $value);
        }

        return $value;
    }
}
