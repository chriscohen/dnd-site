<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property SourceEdition $edition
 * @property Company $origin
 * @property string $product_id
 * @property Uuid $source_edition_id
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

    public function toArrayLong(): array
    {
        return [
            'edition' => $this->edition->toArray($this->renderMode, $this->excluded),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'origin' => $this->origin->toArray(JsonRenderMode::FULL),
            'product_id' => $this->product_id,
            'url' => $this->url(),
        ];
    }

    public function url(): ?string
    {
        return empty($this->origin->product_url) ?
            null :
            $this->origin->website . '/' . $this->origin->getProductUrl($this->product_id);
    }
}
