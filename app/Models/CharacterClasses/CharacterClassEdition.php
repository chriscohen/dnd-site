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
 * @property ?string $alternateName
 * @property ?string $caption
 * @property CharacterClass $characterClass
 * @property GameEdition $gameEdition
 * @property ?int $hitDieFaces
 * @property bool $isGroupOnly
 * @property bool $isPrestige
 * @property ?CharacterClassEdition $parent
 * @property ?Uuid $parentId
 * @property Collection<Reference> $references
 */
class CharacterClassEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'gameEdition' => GameEdition::class,
        'isGroupOnly' => 'boolean',
        'isPrestige' => 'boolean',
    ];

    public function characterClass(): BelongsTo
    {
        return $this->belongsTo(CharacterClass::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CharacterClassEdition::class, 'parentId');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function toArrayFull(): array
    {
        return [
            'hitDieFaces' => $this->hitDieFaces,
            'parent' => $this->parent?->toArray($this->renderMode),
            'references' => ModelCollection::make($this->references)->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'gameEdition' => $this->gameEdition->toStringShort(),
            'isGroupOnly' => $this->isGroupOnly,
            'isPrestige' => $this->isPrestige,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'alternate_name' => $this->alternateName,
            'caption' => $this->caption,
        ];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
