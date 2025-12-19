<?php

declare(strict_types=1);

namespace App\Models\Dice;

use App\Castables\AsDiceFormula;
use Illuminate\Contracts\Database\Eloquent\Castable;

/**
 */
class DiceFormula implements Castable
{
    public function __construct(
        protected string $formula
    ) {
    }

    public static function castUsing(array $arguments): string
    {
        return AsDiceFormula::class;
    }

    public function roll(): int
    {
        preg_match('/^(\d+)d(\d+)(?:([+-])(\d+))?$/i', $this->formula, $matches);

        if (empty($matches)) {
            return 0;
        }

        $count = (int) $matches[1];
        $sides = (int) $matches[2];
        $operator = $matches[3] ?? '+';
        $modifier = (int) ($matches[4] ?? 0);

        $total = 0;
        for ($i = 0; $i < $count; $i++) {
            $total += random_int(1, $sides);
        }

        return $operator === '+' ? $total + $modifier : $total - $modifier;
    }

    public function __toString(): string
    {
        return $this->formula;
    }
}
