<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property SourceEdition $edition
 * @property Company $origin
 * @property string $product_id
 * @property Source $source
 * @property Uuid $source_id
 */
class ProductId extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'origin_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function toArrayFull(): array
    {
        return [
            'sourceId' => $this->source_id,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'origin' => $this->origin->toArray(JsonRenderMode::FULL),
            'productId' => $this->product_id,
            'url' => $this->url(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function url(): ?string
    {
        return empty($this->origin->product_url) ?
            null :
            $this->origin->website . '/' . $this->origin->getProductUrl($this->product_id);
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->product_id = $value['productId'];

        $company = Company::query()->where('slug', $value['company'])->firstOrFail();
        $item->origin()->associate($company);

        $item->source()->associate($parent);

        $item->save();
        return $item;
    }
}
