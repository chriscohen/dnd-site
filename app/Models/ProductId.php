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
 * @property string $productId
 * @property Uuid $sourceEditionId
 */
class ProductId extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'originId');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'sourceId');
    }

    public function toArrayFull(): array
    {
        return [
            'edition' => $this->edition->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'origin' => $this->origin->toArray(JsonRenderMode::FULL),
            'productId' => $this->productId,
            'url' => $this->url(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function url(): ?string
    {
        return empty($this->origin->productUrl) ?
            null :
            $this->origin->website . '/' . $this->origin->getProductUrl($this->productId);
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
