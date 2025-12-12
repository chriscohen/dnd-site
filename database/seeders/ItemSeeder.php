<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Items\ItemType;

class ItemSeeder extends AbstractYmlSeeder
{
    protected string $dir = 'items';
    protected string $model = ItemType::class;
    protected array $excludedProperties = [
        'cover_image'
    ];

    public function run(): void
    {
        foreach ($this->getDataFromDirectory() as $datum) {
            print "Creating Item " . $datum['name'] . "...\n";
            ItemType::fromInternalJson($datum);
        }
    }
}
