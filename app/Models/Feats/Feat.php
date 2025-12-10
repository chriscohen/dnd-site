<?php

declare(strict_types=1);

namespace App\Models\Feats;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<FeatEdition> $editions
 */
class Feat extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function editions(): HasMany
    {
        return $this->hasMany(FeatEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
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
