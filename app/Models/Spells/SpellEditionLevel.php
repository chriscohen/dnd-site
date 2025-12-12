<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;
use App\Models\ModelInterface;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Maps a spell to a class, such as Wizard 5 (5th level wizard spell) etc.
 *
 * @property Uuid $id
 *
 * @property CharacterClass|FeatEdition $entity
 * @property Uuid $entity_id
 * @property string $entity_type
 * @property int $item
 * @property int $level
 * @property Collection<Reference> $references
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

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'spell_edition_id');
    }

    public function toArrayFull(): array
    {
        $output = [
            'spell_edition' => $this->spellEdition?->toArray($this->renderMode),
        ];
        if ($this->entity::class == FeatEdition::class) {
            $output['feat_edition'] = $this->entity->toArray($this->renderMode);
        } else {
            $output['character_class'] = $this->entity->toArray($this->renderMode);
        }
        return $output;
    }

    public function toArrayShort(): array
    {
        return [
            'name' => $this->entity->getName(),
            'slug' => $this->entity->getSlug(),
            'level' => $this->level,
            'type' => $this->entity::class == FeatEdition::class ? 'feat' : 'class',
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->spellEdition()->associate($parent);

        if (!empty($value['class'])) {
            $entity = CharacterClass::query()->where('id', $value['class'])->firstOrFail();
        } else {
            $feat = Feat::query()->where('id', $value['feat'])->firstOrFail();
            $entity = $feat->editions()->firstOrFail();
        }
        $item->entity()->associate($entity);
        $item->level = $value['level'];

        $item->save();
        return $item;
    }
}
