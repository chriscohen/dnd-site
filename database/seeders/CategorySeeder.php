<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;

class CategorySeeder extends AbstractYmlSeeder
{
    protected string $path = 'categories.json';
    protected string $model = Category::class;
    protected array $schema = [
        'id',
        'slug',
        'name',
        'entity_type',
        'parent',
        'image',
    ];

    protected array $excludedProperties = [
        'parent',
        'image',
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        // For each item in the JSON file...
        foreach ($data as $datum) {
            Category::fromInternalJson($datum, null);
        }
    }

    public function postSave(Model $model, array $datum): Model
    {
        /** @var Category $model */
        $model->parentId = !empty($datum['parentId']) ? $datum['parentId'] : null;

        if (!empty($datum['image'])) {
            $media = Media::createFromExisting([
                'filename' => '/categories/' . $datum['image'],
                'disk' => 's3',
                'collectionName' => 'images',
            ]);

            $model->image()->associate($media);
        }

        $model->save();
        return $model;
    }
}
