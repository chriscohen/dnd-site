<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<SpellEdition> $editions
 * @property string $name
 * @property string $slug
 */
class Spell extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'range_is_touch' => 'boolean',
        'range_is_self' => 'boolean',
    ];

    public function editions(): HasMany
    {
        return $this->hasMany(SpellEdition::class);
    }
}
