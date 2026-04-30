<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Dice\DiceFormula;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\UnitTestCase;

final class DiceFormulaTest extends UnitTestCase
{
    #[DataProvider('provideToString')]
    public function testToString(DiceFormula $formula, string $expected): void
    {
        $this->assertEquals($expected, $formula->__toString());
    }

    public function testToStringNoSpaces(): void
    {
        $formula = new DiceFormula('1d6 + 2');
        $this->assertEquals('1d6+2', $formula->toString(withSpaces: false));
        $this->assertEquals('1d6 + 2', $formula->toString(withSpaces: true));
    }

    public static function provideToString(): array
    {
        $formula1 = new DiceFormula();
        $formula1->diceCount = 1;
        $formula1->diceFaces = 6;
        $formula1->modifier = 0;

        $formula2 = new DiceFormula();
        $formula2->diceCount = 1;
        $formula2->diceFaces = 6;
        $formula2->modifier = 2;

        $formula3 = new DiceFormula();
        $formula3->diceCount = 1;
        $formula3->diceFaces = 6;
        $formula3->modifier = -2;

        return [
            'no modifier' => [$formula1, '1d6'],
            'positive modifier' => [$formula2, '1d6+2'],
            'negative modifier' => [$formula3, '1d6-2'],
        ];
    }
}
