<?php

declare(strict_types=1);

namespace Tests\Feature\Creatures;

use App\Models\Creatures\CreatureMajorType;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class CreatureMajorTypeTest extends TestCase
{
    /**
     * @param array{
     *     id: string,
     *     slug: ?string,
     *     name: string,
     *     plural: ?string,
     *     description: ?string,
     *     editions: array{
     *         array{
     *             description: string,
     *             gameEdition: string,
     *         }
     *     }
     * } $value
     */
    #[DataProvider('dataInternal')]
    public function testFromInternalJson(
        array $value,
        string $expectedSlug,
        int $expectedEditionsCount
    ): void {
        $creatureMajorType = CreatureMajorType::fromInternalJson($value);
        $this->assertEquals($expectedSlug, $creatureMajorType->slug);
        $this->assertEquals($expectedEditionsCount, $creatureMajorType->editions->count());
    }

    public static function dataInternal(): array
    {
        $correctData = [
            'id' => '0acec9fa-2c8f-4470-a5de-98ea17d0bf28',
            'name' => 'slug test',
            'plural' => 'slug tests',
            'editions' => [
                [
                    'description' => 'edition desc 1',
                    'gameEdition' => '5e'
                ]
            ]
        ];

        return [
            'Correct data' => [
                'value' => $correctData,
                'expectedSlug' => 'slug-test',
                'expectedEditionsCount' => 1,
            ]
        ];
    }
}
