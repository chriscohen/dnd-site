<?php

namespace App\Models\Spells;

use App\Enums\Distance;
use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Enums\MaterialComponentMode;
use App\Enums\SpellComponentType;
use App\Models\AbstractModel;
use App\Models\Items\ItemEdition;
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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 *
 * @property Collection<SpellEditionCharacterClassLevel> $classLevels
 * @property string $description
 * @property Collection<MagicDomain> $domains
 * @property ?string $focus
 * @property GameEdition $game_edition
 * @property string $higher_level
 * @property bool $is_default
 * @property Collection<ItemEdition> $itemEditions
 * @property MaterialComponentMode $material_component_mode
 * @property Collection<SpellMaterialComponent> $materialComponents
 * @property Range $range
 * @property Uuid $range_id
 * @property MagicSchool $school
 * @property Spell $spell
 * @property string $spell_components
 * @property Uuid $spell_id
 */
class SpellEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function toArrayLong(): array
    {
        return [
            'class_levels' => ModelCollection::make($this->classLevels)
                ->toArray(JsonRenderMode::SHORT, $this->excluded),
            'description' => $this->description,
            'domains' => ModelCollection::make($this->domains)->toArray($this->renderMode, $this->excluded),
            'focus' => $this->focus,
            'game_edition' => $this->game_edition,
            'higher_level' => $this->higher_level,
            'is_default' => $this->is_default,
            'item_editions' => ModelCollection::make($this->itemEditions)->toArray($this->renderMode, $this->excluded),
            'lowest_level' => $this->getLowestLevel(),
            'material_component_mode' => $this->material_component_mode,
            'material_components' => ModelCollection::make($this->materialComponents)->toArray(
                $this->renderMode,
                $this->excluded
            ),
            'range' => $this->range->toArray($this->renderMode, $this->excluded),
            'school' => $this->school?->toArray($this->renderMode, $this->excluded) ?? null,
            'spell' => $this->spell->toArray($this->renderMode, $this->excluded),
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

    public $casts = [
        'game_edition' => GameEdition::class,
        'is_default' => 'bool',
        'material_component_mode' => MaterialComponentMode::class,
        'range_unit' => Distance::class,
    ];

    public array $schema = [
        JsonRenderMode::SHORT->value => [

        ],
        JsonRenderMode::FULL->value => [

        ]
    ];

    public function classLevels(): HasMany
    {
        return $this->hasMany(SpellEditionCharacterClassLevel::class, 'spell_edition_id');
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

    public function itemEditions(): BelongsToMany
    {
        return $this->belongsToMany(ItemEdition::class, 'spell_material_components');
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
}
