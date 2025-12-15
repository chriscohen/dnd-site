<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use App\Models\Sources\Source;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Media $logo
 * @property string $name
 * @property ?string $product_url
 * @property Collection<Source> $products
 * @property ?string $short_name
 * @property string $website
 */
class Company extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function getProductUrl(string|Uuid $id): ?string
    {
        return empty($this->product_url) ?
            null :
            str_replace('{{id}}', $id, $this->product_url);
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Source::class, 'publisher_id');
    }

    public function toArrayFull(): array
    {
        return [
            'logo' => $this->logo?->toArray($this->renderMode),
            'products' => ModelCollection::make($this->products)->toArray(JsonRenderMode::TEASER),
            'productUrl' => $this->product_url,
            'website' => $this->website,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'shortName' => $this->short_name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'] ?? null;
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->productUrl = $value['productUrl'] ?? null;
        $item->shortName = $value['shortName'] ?? null;
        $item->website = $value['website'] ?? null;

        if (!empty($value['logo'])) {
            $image = Media::fromInternalJson([
                'filename' => '/companies/' . $value['logo'],
            ], $parent);
            $item->logo()->associate($image);
        }

        $item->save();
        return $item;
    }
}
