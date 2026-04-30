<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
use App\Enums\PublicationType;
use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property ?string $description
 * @property Media $logo
 * @property Uuid $logo_id
 * @property string $name
 * @property Company $publisher
 * @property Uuid $publisher_id
 * @property PublicationType $publication_type
 * @property string $short_name
 * @property ?int $start_year
 */
class CampaignSetting extends AbstractModel
{
    use HasUuids;
    use Searchable;

    public $timestamps = false;
    protected $primaryKey = 'id';

    public $casts = [
        'publication_type' => PublicationType::class,
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function publicationType(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => PublicationType::tryFrom($value)->toString(),
        );
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'publisher_id');
    }

    public function toArrayFull(): array
    {
        $output = [
            'publication_type' => $this->publication_type,
            'publisher' => $this->publisher->toArray(JsonRenderMode::TEASER),
        ];

        if (!empty($this->start_year)) {
            $output['startYear'] = $this->start_year;
        }
        if (!empty($this->description)) {
            $output['description'] = $this->description;
        }

        return $output;
    }

    public function toArrayShort(): array
    {
        $output = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];

        if (!empty($this->logo)) {
            $output['logo'] = $this->logo->toArray($this->renderMode);
        }

        return $output;
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'] ?? null;
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->short_name = $value['shortName'] ?? null;
        $item->description = $value['description'] ?? null;
        $item->start_year = $value['startYear'] ?? null;

        if (!empty($value['logo'])) {
            $logo = Media::fromInternalJson([
                'filename' => '/campaign-settings/' . $value['logo']
            ]);
            $item->logo()->associate($logo);
        }
        if ($value['publisher']) {
            $publisher = Company::query()->where('slug', $value['publisher'])->firstOrFail();
            $item->publisher()->associate($publisher);
        }
        if (!empty($value['publicationType'])) {
            $item->publication_type = PublicationType::tryFromString($value['publicationType']);
        }

        $item->save();
        return $item;
    }
}
