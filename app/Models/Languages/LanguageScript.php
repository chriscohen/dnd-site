<?php

declare(strict_types=1);

namespace App\Models\Languages;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<Language> $languages
 */
class LanguageScript extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }

    public function toArrayFull(): array
    {
        return [
            'languages' => ModelCollection::make($this->languages)->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        // Make sure it doesn't already exist.
        $script = static::query()->where('name', $value)->first();

        if (!empty($script)) {
            return $script;
        }

        // It doesn't exist, so create it.
        $item = new static();
        $item->id = Uuid::uuid4();
        $item->name = $value;
        $item->slug = static::makeSlug($value);
        $item->save();
        return $item;
    }
}
