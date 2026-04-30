<?php

declare(strict_types=1);

namespace App\Models\Dice;

use App\Castables\AsDiceFormula;
use Illuminate\Contracts\Database\Eloquent\Castable;

/**
 */
class DiceFormula implements Castable
{
    public string $formula;
    public int $diceCount;
    public int $diceFaces;
    public int $modifier;

    public function __construct(
        ?string $formula = null
    ) {
        $this->setFormula($formula ?? '');
    }

    public static function castUsing(array $arguments): string
    {
        return AsDiceFormula::class;
    }

    public function roll(): int
    {
        $total = 0;
        for ($i = 0; $i < $this->diceCount; $i++) {
            $total += random_int(1, $this->diceCount);
        }

        return $total + $this->modifier;
    }

    public function setFormula(string $formula): static
    {
        $formula = str_replace(' ', '', $formula);
        $this->formula = $formula;
        preg_match('/^(\d+)d(\d+)(?:([+-])(\d+))?$/i', $this->formula, $matches);

        if (empty($matches)) {
            $this->diceCount = 0;
            $this->diceFaces = 0;
            $this->modifier = 0;
            return $this;
        }

        $this->diceCount = (int) $matches[1];
        $this->diceFaces = (int) $matches[2];
        $operator = $matches[3] ?? '+';
        $this->modifier = (int) ($matches[4] ?? 0);
        $this->modifier *= $operator === '+' ? 1 : -1;

        return $this;
    }

    public function toString(?bool $withSpaces = false): string
    {
        $spaceCharacter = $withSpaces ? ' ' : '';
        $operator = $this->modifier > 0 ? '+' : '-';
        $modifier = $this->modifier === 0 ? '' : $spaceCharacter . $operator . $spaceCharacter . abs($this->modifier);

        return $this->diceCount . 'd' . $this->diceFaces . $modifier;
    }


    public function __toString(): string
    {
        return $this->toString();
    }
}
