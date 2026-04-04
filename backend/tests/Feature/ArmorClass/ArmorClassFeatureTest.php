<?php

declare(strict_types=1);

namespace Tests\Feature\ArmorClass;

use App\Models\ArmorClass\ArmorClass;
use App\Models\Creatures\CreatureType;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\FeatureTestCase;

final class ArmorClassFeatureTest extends FeatureTestCase
{
    protected CreatureType $creature;

    public function setUp(): void
    {
        parent::setUp();

        $this->creature = CreatureType::generate();
    }

    #[DataProvider('data')]
    public function testFrom5eJson(string|array $value, int $expectedAc): void
    {
        $ac = ArmorClass::from5eJson($value);
        $this->assertEquals($expectedAc, $ac->value);
    }

    public static function data(): array
    {
        $justNumber = [
            'value' => '13',
            'expectedAc' => 13,
        ];

        return [
            'Just number' => $justNumber,
        ];
    }
}
