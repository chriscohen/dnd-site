<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
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
            'spell_editions_4e_traits',
            'spell_edition_4e_id',
            'spell_trait_id'
        );
    }

    public function toArrayFull(): array
    {
        return [
            'spell_edition' => ModelCollection::make($this->spellEditions4e)->toArray(),
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        throw new \Exception('Not implemented');
    }
}
