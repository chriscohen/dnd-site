<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * 4th edition spell traits such as Arcane, Implement, Polymorph, etc.
 *
 * @property string $id
 *
 * @property Collection<SpellEdition4e> $spellEditions4e
 */
class SpellTrait extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function spellEditions4e(): BelongsToMany
    {
        return $this->belongsToMany(
            SpellEdition4e::class,
            'spellEditions4eTraits',
            'spellEdition4eId',
            'spellTraitId'
        );
    }

    public function toArrayFull(): array
    {
        return [
            'spellEdition' => ModelCollection::make($this->spellEditions4e)->toArray(),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
