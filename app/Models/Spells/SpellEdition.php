<?php

namespace App\Models\Spells;

use App\Enums\Distance;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\MagicSchool;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property string $description
 * @property GameEdition $game_edition
 * @property string $higher_level
 * @property bool $is_default
 * @property MagicSchool $school
 * @property bool $range_is_self
 * @property bool $range_is_touch
 * @property int $range_number
 * @property Distance $range_unit
 * @property Spell $spell
 * @property Uuid $spell_id
 * @property Collection<SpellComponentType> $spellComponents
 */
class SpellEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'is_default' => 'bool',
        'range_unit' => Distance::class,
    ];

    public function componentsAsString(): string
    {
        $output = '';

        foreach ($this->spellComponents as $component) {
            $output .= $component->id;
        }

        return $output;
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => GameEdition::tryFrom($value)->toString(),
        );
    }

    public function spellComponents(): BelongsToMany
    {
        return $this->belongsToMany(SpellComponentType::class, 'spell_edition_spell_component_types');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(MagicSchool::class, 'magic_school_id');
    }

    public function schoolAsString(): string
    {
        return $this->school->name;
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function spell(): BelongsTo
    {
        return $this->belongsTo(Spell::class);
    }
}
