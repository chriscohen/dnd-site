<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
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

    public function doExtras(Model $model, array $datum): Model
    {
        if (!empty($datum['parent'])) {
            $parent = Category::query()->where('slug', $datum['parent'])->firstOrFail();
            /** @var Category $model */
            $model->parent()->associate($parent);
            $model->save();
        }

        if (!empty($datum['image'])) {
            /** @var Category $model */
            $model->addMediaFromDisk('/categories/' . $datum['image'], 's3')
                ->preservingOriginal()
                ->toMediaCollection('image');
        }

        return $model;
    }
}
