<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Media $logo
 * @property Uuid $logoId
 * @property string $name
 * @property Company $publisher
 * @property Uuid $publisherId
 * @property PublicationType $publicationType
 * @property string $shortName
 */
class CampaignSetting extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    protected $primaryKey = 'id';

    public $casts = [
        'publicationType' => PublicationType::class,
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logoId');
    }

    public function publicationType(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => PublicationType::tryFrom($value)->toString(),
        );
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'publisherId');
    }

    public function toArrayFull(): array
    {
        return [
            'logo' => $this->logo?->toArray($this->renderMode),
            'publicationType' => $this->publicationType,
            'publisher' => $this->publisher->toArray($this->renderMode),
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
        $item = new static();
        $item->id = $value['id'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);

        return $item;
    }
}
