<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Data\ConcreteYmlSeeder;
use Tests\TestCase;

class AbstractYmlSeederTest extends TestCase
{
    #[DataProvider('providerRemoveExcludedProperties')]
    public function testRemoveExcludedProperties(array $data, array $excluded, array $expected): void
    {
        $seeder = new ConcreteYmlSeeder();
        $seeder->setExcludedProperties($excluded);
        $result = $seeder->removeExcludedProperties($data);
        $this->assertEquals($expected, $result);
    }

    public static function providerRemoveExcludedProperties(): array
    {
        return [
            'no removal' => [
                ['id' => '123', 'name' => 'test'],
                [],
                ['id' => '123', 'name' => 'test'],
            ],
            'removal' => [
                ['id' => '123', 'name' => 'test'],
                ['name'],
                ['id' => '123'],
            ]
        ];
    }
}
