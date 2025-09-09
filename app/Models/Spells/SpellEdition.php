<?php

namespace App\Models\Spells;

use App\Enums\Distance;
use App\Enums\GameEdition;
use App\Enums\MaterialComponentMode;
use App\Models\AbstractModel;
use App\Models\Items\ItemEdition;
use App\Models\Magic\MagicDomain;
use App\Models\Magic\MagicSchool;
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
 * @property GameEdition $game_edition
 * @property string $higher_level
 * @property bool $is_default
 * @property Collection<ItemEdition> $itemEditions
 * @property Collection<SpellMaterialComponent> $materialComponents
 * @property MagicSchool $school
 * @property MaterialComponentMode $material_component_mode
 * @property bool $range_is_self
 * @property bool $range_is_touch
 * @property int $range_number
 * @property int $range_per_level
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
        'material_component_mode' => MaterialComponentMode::class,
        'range_unit' => Distance::class,
    ];

    public function classLevels(): HasMany
    {
        return $this->hasMany(SpellEditionCharacterClassLevel::class, 'spell_edition_id');
    }

    public function componentsAsString(): string
    {
        $output = '';

        foreach ($this->spellComponents as $component) {
            $output .= $component->id;
        }

        return $output;
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

    protected function rangeUnit(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => $value === null ? '' : Distance::tryFrom($value)->toString(),
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
