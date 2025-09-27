<?php

declare(strict_types=1);

namespace App\Models\Feats;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\Prerequisites\Prerequisite;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property ?string $description
 * @property Collection<Prerequisite> $prerequisites
 */
class FeatEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function feat(): BelongsTo
    {
        return $this->belongsTo(Feat::class);
    }

    public function prerequisites(): HasMany
    {
        return $this->hasMany(Prerequisite::class);
    }

    public function toArrayFull(): array
    {
        return [
            'feat' => $this->feat->toArray(JsonRenderMode::SHORT),
            'prerequisites' => ModelCollection::make($this->prerequisites)->toArray($this->renderMode),
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
        return [
            'description' => $this->description,
        ];
    }
}
