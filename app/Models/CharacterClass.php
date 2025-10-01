<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 *
 * @property bool $is_prestige
 * @property Collection<Reference> $references
 */
class CharacterClass extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public $casts = [
        'is_prestige' => 'boolean',
    ];

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'is_prestige' => $this->is_prestige,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
