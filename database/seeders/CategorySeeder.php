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

    public function postSave(Model $model, array $datum): Model
    {
        if (!empty($datum['parent'])) {
            $parent = Category::query()->where('slug', $datum['parent'])->firstOrFail();
            /** @var Category $model */
            $model->parent()->associate($parent);
        }

        if (!empty($datum['image'])) {
            $media = Media::createFromExisting([
                'filename' => '/categories/' . $datum['image'],
                'disk' => 's3',
                'collection_name' => 'images',
            ]);

            $model->image()->associate($media);
        }

        $model->save();
        return $model;
    }
}
