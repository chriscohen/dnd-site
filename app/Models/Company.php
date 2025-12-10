<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Media $logo
 * @property string $name
 * @property ?string $productUrl
 * @property ?string $shortName
 * @property string $website
 */
class Company extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function getProductUrl(string|Uuid $id): ?string
    {
        return empty($this->productUrl) ?
            null :
            str_replace('{{id}}', $id, $this->productUrl);
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logoId');
    }

    public function toArrayFull(): array
    {
        return [
            'logo' => $this->logo->toArray($this->renderMode),
            'productUrl' => $this->productUrl,
            'shortName' => $this->shortName,
            'website' => $this->website,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
