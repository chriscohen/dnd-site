<?php

declare(strict_types=1);

namespace App\Models\Items;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Collection<ItemEdition> $editions
 * @property string $name
 */
class Item extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function editions(): HasMany
    {
        return $this->hasMany(ItemEdition::class);
    }
}
