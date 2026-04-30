<?php

declare(strict_types=1);

namespace App\Models\Proficiencies;

use App\Enums\AbilityScoreType;
use App\Enums\Proficiencies\ProficiencyType;
use App\Models\AbstractModel;
use App\Models\CharacterClasses\CharacterClassEdition;
use App\Models\ModelInterface;
use App\Models\Skills\Skill;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property CharacterClassEdition $characterClassEdition
 * @property Uuid $character_class_edition_id
 * @property ?Skill $entity
 * @property ?Uuid $entity_id
 * @property ?string $entity_type
 * @property ProficiencyType $proficiency_type
 * @property ?int $value
 */
class StartingProficiency extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'starting_proficiencies';

    public $casts = [
        'proficiency_type' => ProficiencyType::class,
    ];

    public function characterClassEdition(): BelongsTo
    {
        return $this->belongsTo(CharacterClassEdition::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function getValue()
    {
        return match ($this->proficiency_type) {
            ProficiencyType::ABILITY => AbilityScoreType::tryFrom($this->value),
            ProficiencyType::ARMOR =>
        };
    }

    public static function fromInternalJson(array|int|string $value, ModelInterface $parent = null): static
    {
        $item = new static();

        return $item;
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->proficiency_type->toString(),
            'value' => $this->value
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
