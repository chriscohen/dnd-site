<?php

declare(strict_types=1);

namespace App\Models\StatusConditions;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<StatusConditionEdition> $editions
 */
class StatusCondition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function editions(): HasMany
    {
        return $this->hasMany(StatusConditionEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->rules)->toArray($this->renderMode, $this->excluded),
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
            'editions' => ModelCollection::make($this->rules)->toArray($this->renderMode, $this->excluded),
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
