<?php

declare(strict_types=1);

namespace Tests\Feature\Sources;

use App\Models\Sources\Source;
use Tests\TestCase;

final class SourceTest extends TestCase
{
    public function testFrom5eJson(): void
    {
        $source = Source::from5eJson(self::data());
        $this->assertEquals(1, $source->editions->count());
    }

    public static function data(): array
    {
        return [
            'name' => 'Adventure Atlas: The Mortuary',
            'id' => 'AATM',
            'source' => 'AATM',
            'group' => 'supplemental-alt',
            'cover' => [
                'type' => 'internal',
                'path' => 'covers/AATM.webp',
            ],
            'published' => '2023-10-17',
            'author' => 'Wizards RPG Team',
            'contents' => [
                [
                    'name' => 'Adventure Atlas: The Mortuary',
                    'headers' => [
                        'Using This Supplement',
                        'Heralds of Dust',
                        'The Mortuary',
                        'Appendix: Mortuary Creatures',
                    ]
                ],
                [
                    'name' => 'Credits',
                ]
            ]
        ];
    }
}
