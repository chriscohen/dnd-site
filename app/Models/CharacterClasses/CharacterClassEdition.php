<?php

declare(strict_types=1);

namespace App\Models\CharacterClasses;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?string $alternate_name
 * @property ?string $caption
 * @property CharacterClass $characterClass
 * @property GameEdition $game_edition
 * @property ?int $hit_die_faces
 * @property bool $is_prestige
 * @property ?CharacterClassEdition $parent
 * @property ?Uuid $parent_id
 * @property Collection<Reference> $references
 */
class CharacterClassEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'is_prestige' => 'boolean',
    ];

    public function characterClass(): BelongsTo
    {
        return $this->belongsTo(CharacterClass::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CharacterClassEdition::class, 'parent_id');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function toArrayFull(): array
    {
        return [
            'hit_die_faces' => $this->hit_die_faces,
            'parent' => $this->parent()->toArray($this->renderMode),
            'references' => ModelCollection::make($this->references)->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'game_edition' => $this->game_edition->toStringShort(),
            'is_prestige' => $this->is_prestige,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'alternate_name' => $this->alternate_name,
            'caption' => $this->caption,
        ];
    }
}
