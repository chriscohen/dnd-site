<?php

namespace App\Models\Spells;

use App\Enums\Distance;
use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Enums\SavingThrows\SavingThrowMultiplier;
use App\Enums\SavingThrows\SavingThrowType;
use App\Enums\Spells\MaterialComponentMode;
use App\Enums\Spells\SpellComponentType;
use App\Enums\Spells\SpellFrequency;
use App\Enums\TimeUnit;
use App\Models\AbstractModel;
use App\Models\Area;
use App\Models\DamageInstance;
use App\Models\Duration;
use App\Models\Magic\MagicDomain;
use App\Models\Magic\MagicSchool;
use App\Models\ModelCollection;
use App\Models\Range;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 *
 * @property ?Area $area
 * @property int $casting_time_number
 * @property TimeUnit $casting_time_unit
 * @property Collection<SpellEditionCharacterClassLevel> $classLevels
 * @property Collection<DamageInstance> $damageInstances
 * @property string $description
 * @property Collection<MagicDomain> $domains
 * @property Duration $duration
 * @property ?string $focus
 * @property GameEdition $game_edition
 * @property string $gameEdition,
 * @property ?bool $has_saving_throw
 * @property ?bool $has_spell_resistance
 * @property string $higher_level
 * @property bool $is_default
 * @property MaterialComponentMode $material_component_mode
 * @property Collection<SpellMaterialComponent> $materialComponents
 * @property Range $range
 * @property Uuid $range_id
 * @property Collection<Reference> $references
 * @property ?SavingThrowMultiplier $saving_throw_multiplier
 * @property ?SavingThrowType $saving_throw_type
 * @property MagicSchool $school
 * @property Spell $spell
 * @property string $spell_components
 * @property ?SpellEdition4e $spellEdition4e
 * @property Uuid $spell_id
 */
class SpellEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'casting_time_unit' => TimeUnit::class,
        'frequency' => SpellFrequency::class,
        'game_edition' => GameEdition::class,
        'has_saving_throw' => 'bool',
        'has_spell_resistance' => 'bool',
        'is_default' => 'bool',
        'material_component_mode' => MaterialComponentMode::class,
        'range_unit' => Distance::class,
        'saving_throw_multiplier' => SavingThrowMultiplier::class,
        'saving_throw_type' => SavingThrowType::class,
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function classLevels(): HasMany
    {
        return $this->hasMany(SpellEditionCharacterClassLevel::class, 'spell_edition_id');
    }

    public function damageInstances(): MorphMany
    {
        return $this->morphMany(DamageInstance::class, 'entity');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => app(MarkdownRenderer::class)->toHtml($value),
        );
    }

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(MagicDomain::class, 'spell_editions_magic_domains');
    }

    public function duration(): MorphOne
    {
        return $this->morphOne(Duration::class, 'entity');
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => GameEdition::tryFrom($value)->toStringShort(),
        );
    }

    /**
     * @return string[]
     */
    public function getDomainsAsStringArray(): array
    {
        $output = [];

        foreach ($this->domains as $domain) {
            $output[] = $domain->name;
        }

        return $output;
    }

    public function getLowestLevel(): int
    {
        $lowest = 99;

        foreach ($this->classLevels as $classLevel) {
            if ($classLevel->level < $lowest) {
                $lowest = $classLevel->level;
            }
        }

        return $lowest;
    }

    public function hasComponent(SpellComponentType $componentType): bool
    {
        return str_contains($this->spell_components, $componentType->value);
    }

    protected function materialComponentMode(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => $value === null ? null : MaterialComponentMode::tryFrom($value)->toString(),
        );
    }

    public function materialComponents(): HasMany
    {
        return $this->hasMany(SpellMaterialComponent::class);
    }

    public function range(): BelongsTo
    {
        return $this->belongsTo(Range::class);
    }

    public function rangeAsString(): string
    {
        return $this->range->toString();
    }

    protected function rangeUnit(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => $value === null ? '' : Distance::tryFrom($value)->toString(),
        );
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

    public function spellEdition4e(): HasOne
    {
        return $this->hasOne(SpellEdition4e::class);
    }

    public function toArrayFull(): array
    {
        return [
            'area' => $this->area?->toArray($this->renderMode) ?? null,
            'casting_time' => $this->casting_time_unit->format($this->casting_time_number),
            'class_levels' => ModelCollection::make($this->classLevels)
                ->toArray(),
            'damage_instances' => ModelCollection::make($this->damageInstances)
                ->toArray(),
            'description' => $this->description,
            'domains' => ModelCollection::make($this->domains)->toArray($this->renderMode),
            'duration' => $this->duration->toArray($this->renderMode),
            'focus' => $this->focus,
            'has_saving_throw' => $this->has_saving_throw,
            'has_spell_resistance' => $this->has_spell_resistance,
            'higher_level' => $this->higher_level,
            'is_default' => $this->is_default,
            'lowest_level' => $this->getLowestLevel(),
            'material_component_mode' => $this->material_component_mode,
            'material_components' => ModelCollection::make($this->materialComponents)->toArray($this->renderMode),
            'range' => $this->range->toArray($this->renderMode),
            'references' => ModelCollection::make($this->references)->toArray(JsonRenderMode::TEASER),
            'saving_throw_multiplier' => $this->saving_throw_multiplier?->toString(),
            'saving_throw_type' => $this->saving_throw_type?->toString(),
            'school' => $this->school?->toArray($this->renderMode) ?? null,
            'spell_components' => $this->spell_components,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'spell_id' => $this->spell_id,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'game_edition' => $this->game_edition,
        ];
    }
}
