<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\CharacterClass;
use App\Models\Feats\FeatEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ModelInterface $entity
 * @property Uuid $entity_id
 * @property string $entity_type
 * @property int $level
 * @property SpellEdition $spellEdition
 * @property Uuid $spell_edition_id
 */
class SpellEditionLevel extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'spell_edition_id');
    }

    public function toArrayFull(): array
    {
        return [
            'spell_edition' => $this->spellEdition?->toArray($this->renderMode, $this->excluded),
            'character_class' => $this->characterClass->toArray($this->renderMode, $this->excluded),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'name' => $this->entity->getName(),
            'level' => $this->level,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
