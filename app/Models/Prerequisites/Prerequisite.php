<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\Alignment;
use App\Enums\Prerequisites\PrerequisiteType;
use App\Enums\SpellcasterType;
use App\Models\AbstractModel;
use App\Models\CharacterClass;
use App\Models\Deity;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;
use App\Models\ModelCollection;
use App\Models\Species;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property FeatEdition $featEdition
 * @property PrerequisiteType $type
 * @property Collection<PrerequisiteValue> $values
 */
class Prerequisite extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'type' => PrerequisiteType::class,
    ];

    public function featEdition(): BelongsTo
    {
        return $this->belongsTo(FeatEdition::class);
    }

    /**
     * @return Collection<string|Deity|Alignment|\App\Models\Feats\Feat|CharacterClass|SpellcasterType>
     */
    public function getValues(): Collection
    {
        $output = new Collection();

        foreach ($this->values as $value) {
            $output->add(match ($this->type) {
                PrerequisiteType::MINIMUM_LEVEL,
                PrerequisiteType::MINIMUM_BASE_ATTACK_BONUS => $value->value,
                PrerequisiteType::PATRON_DEITY => Deity::query()->where('id', $value->value)->first(),
                PrerequisiteType::ALIGNMENT => Alignment::tryFromString($value->value),
                PrerequisiteType::FEAT => Feat::query()->where('id', $value->value)->first(),
                PrerequisiteType::CHARACTER_CLASS => CharacterClass::query()->where('id', $value->value)->first(),
                PrerequisiteType::SPELLCASTER_TYPE => SpellcasterType::tryFromString($value->value),
                PrerequisiteType::SPECIES => Species::query()->where('id', $value->value)->first(),
            });
        }

        return $output;
    }

    public function toArrayFull(): array
    {
        return [
            'feat_edition' => $this->featEdition->toArray(),
            'id' => $this->id,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'type' => $this->type->toString(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'values' => ModelCollection::make($this->values)->toArray(),
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(PrerequisiteValue::class);
    }
}
