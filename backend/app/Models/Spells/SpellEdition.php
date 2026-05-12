<?php

namespace App\Models\Spells;

use App\Enums\GameEdition;
use App\Enums\Rarity;
use App\Enums\Spells\MaterialComponentMode;
use App\Enums\Spells\SpellComponentType;
use App\Enums\Spells\SpellFrequency;
use App\Enums\Units\DistanceUnit;
use App\Models\AbstractModel;
use App\Models\Area;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\Duration;
use App\Models\Effects\Effect;
use App\Models\Feats\Feature;
use App\Models\Magic\MagicDomain;
use App\Models\Magic\MagicSchool;
use App\Models\ModelInterface;
use App\Models\Range;
use App\Models\Reference;
use App\Models\SavingThrow;
use App\Models\Sources\Source;
use App\Models\Target;
use App\Models\Text\TextEntry;
use App\Services\FeToolsService;
use App\Traits\WithTextEntries;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 *
 * @property ?Area $area
 * @property ?SpellCastingTime $castingTime
 * @property Collection<SpellCastingTime> $castingTimes
 * @property Collection<MagicDomain> $domains
 * @property Duration $duration
 * @property Collection<Effect> $effects
 * @property Collection<TextEntry> $entries
 * @property ?Feature $feat
 * @property ?string $focus
 * @property string $game_edition
 * @property ?bool $has_spell_resistance
 * @property bool $is_default
 * @property Collection<SpellEditionLevel> $levels
 * @property ?MaterialComponentMode $material_component_mode
 * @property Collection<SpellMaterialComponent> $materialComponents
 * @property ?Range $range
 * @property ?Uuid $range_id
 * @property Rarity $rarity
 * @property Collection<Reference> $references
 * @property ?SavingThrow $savingThrow
 * @property MagicSchool $school
 * @property Spell $spell
 * @property string $spell_components
 * @property ?SpellEdition4e $spellEdition4e
 * @property Uuid $spell_id
 * @property Collection<Target> $targets
 */
class SpellEdition extends AbstractModel
{
    use HasUuids;
    use WithTextEntries;

    public $timestamps = false;

    public $casts = [
        'frequency' => SpellFrequency::class,
        'game_edition' => GameEdition::class,
        'has_spell_resistance' => 'bool',
        'is_default' => 'bool',
        'material_component_mode' => MaterialComponentMode::class,
        'range_unit' => DistanceUnit::class,
        'rarity' => Rarity::class,
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function castingTime(): Attribute
    {
        return Attribute::get(fn () => $this->castingTimes->first());
    }

    public function castingTimes(): HasMany
    {
        return $this->hasMany(SpellCastingTime::class, 'spell_edition_id');
    }

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(MagicDomain::class, 'spell_editions_magic_domains');
    }

    public function duration(): MorphOne
    {
        return $this->morphOne(Duration::class, 'entity');
    }

    public function effects(): MorphMany
    {
        return $this->morphMany(Effect::class, 'owner');
    }

    public function feat(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'featId');
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

    public function getLowestLevel(): ?int
    {
        $lowest = 999;

        foreach ($this->levels as $level) {
            if ($level->level < $lowest) {
                $lowest = $level->level;
            }
        }

        return $lowest === 999 ? null : $lowest;
    }

    public function hasComponent(SpellComponentType $componentType): bool
    {
        return str_contains($this->spell_components, $componentType->value);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(SpellEditionLevel::class, 'spell_edition_id');
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

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function savingThrow(): HasOne
    {
        return $this->hasOne(SavingThrow::class, 'spell_edition_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(MagicSchool::class, 'magic_school_id');
    }

    public function schoolAsString(): string
    {
        return $this->school->name;
    }

    public function spell(): BelongsTo
    {
        return $this->belongsTo(Spell::class);
    }

    public function spellEdition4e(): HasOne
    {
        return $this->hasOne(SpellEdition4e::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class, 'spell_edition_id');
    }


    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->spell()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->focus = $value['focus'] ?? null;
        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);
        $item->is_default = $value['isDefault'] ?? false;
        $item->material_component_mode = !empty($value['materialComponentMode']) ?
            MaterialComponentMode::tryFromString($value['materialComponentMode']) : null;

        if (!empty($value['rarity'])) {
            $item->rarity = Rarity::tryFromString($value['rarity']);
        }

        if (!empty($value['range'])) {
            $range = Range::fromInternalJson($value['range'], $item);
            $item->range()->associate($range);
        }

        if (!empty($value['description'])) {
            $entry = TextEntry::fromInternalJson($value['description'], $item);
            $item->entries()->save($entry);
        }
        if (!empty($value['higherLevel'])) {
            $entry = TextEntry::fromInternalJson([
                'type' => 'entries',
                'name' => 'At Higher Levels',
                'entries' => [$value['higherLevel']]
            ], $item);
            $item->entries()->save($entry);
        }

        // Area
        if (!empty($value['area'])) {
            $area = Area::fromInternalJson($value['area'], $item);
            $item->area()->associate($area);
        }

        // Domains
        foreach ($value['domains'] ?? [] as $domainData) {
            $domain = MagicDomain::query()->where('id', $domainData)->firstOrFail();
            $item->domains()->attach($domain);
        }

        $item->spell_components = $value['spellComponents'] ?? null;
        $item->has_spell_resistance = $value['hasSpellResistance'] ?? null;

        if (!empty($value['school'])) {
            $school = MagicSchool::query()->where('name', ucfirst($value['school']))->firstOrFail();
            $item->school()->associate($school);
        }

        // Casting time.
        //$item->casting_time_number = $value['castingTimeNumber'] ?? 1;
        //$item->casting_time_unit = TimeUnit::tryFromString($value['castingTimeUnit']);

        $item->save();

        // Saving throws
        if (!empty($value['savingThrow'])) {
            $savingThrow = SavingThrow::fromInternalJson($value['savingThrow'], $item);
            $item->savingThrow()->save($savingThrow);
        }

        // duration.
        $duration = Duration::fromInternalJson($value['duration'], $item);
        $item->duration()->save($duration);

        // 4th edition stuff.
        if ($item->game_edition === GameEdition::FOURTH) {
            $spellEdition4e = SpellEdition4e::fromInternalJson($value, $item);
            $item->spellEdition4e()->save($spellEdition4e);
        }

        // Damage.
        if (!empty($value['damage'])) {
            $effect = Effect::fromInternalJson($value['damage'], $item);
            $item->effects()->save($effect);
        }

        // Levels.
        foreach ($value['levels'] as $levelData) {
            $spellEditionLevel = SpellEditionLevel::fromInternalJson($levelData, $item);
            $item->levels()->save($spellEditionLevel);
        }

        // Material components
        foreach ($value['materialComponents'] ?? [] as $materialData) {
            $materialComponent = SpellMaterialComponent::fromInternalJson($materialData, $item);
            $item->materialComponents()->save($materialComponent);
        }

        // Target
        if (!empty($value['target'])) {
            $target = Target::fromInternalJson($value['target'], $item);
            $item->targets()->save($target);
        }

        // References
        foreach ($value['references'] ?? [] as $reference) {
            Reference::fromInternalJson($reference, $item);
        }

        if (empty($item->game_edition)) {
            throw new \InvalidArgumentException('Spell edition must have a game edition.');
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();

        if (!empty($value['srd52'])) {
            $item->game_edition = GameEdition::FIFTH_REVISED;
        } else {
            $item->game_edition = GameEdition::FIFTH;
        }
        $item->spell()->associate($parent);

        // Magic school.
        $school = MagicSchool::query()->where('short_name', mb_strtoupper($value['school']))->firstOrFail();
        $item->school()->associate($school);

        $item->save();

        /**
         * Casting time.
         */
        if (!empty($value['time'])) {
            foreach ($value['time'] as $timeData) {
                $castingTime = SpellCastingTime::from5eJson($timeData, $item);
                $item->castingTimes()->save($castingTime);
            }
        }

        // Range.
        if (!empty($value['range'])) {
            $range = Range::from5eJson($value['range'], $item);
            $item->range()->associate($range);
        }

        // Duration.
        if (!empty($value['duration'])) {
            foreach ($value['duration'] as $durationData) {
                $duration = Duration::from5eJson($durationData, $item);
                $item->duration()->save($duration);
            }
        }

        $item->save();

        // Spell components.
        if (!empty($value['components'])) {
            $componentPieces = [];

            foreach ($value['components'] as $key => $component) {
                // Keep track of the pieces eg, V, S, etc.
                $componentPieces[] = mb_strtoupper($key);

                // Material component has special values.
                if ($key == 'm') {
                    $material = SpellMaterialComponent::from5eJson($component, $item);
                    $item->materialComponents()->save($material);
                }

                // Join all the components together into a single string.
                $item->spell_components = implode(', ', $componentPieces);
            }
        }

        /**
         * References.
         */
        if (!empty($value['source'])) {
            $source = Source::query()->where('short_name', $value['source'])->firstOrFail();
            Reference::fromInternalJson([
                'source' => $source->slug,
                'editionId' => $source->primaryEdition->id,
                'pageFrom' => $value['page'] ?? null
            ], $item);
        }

        /**
         * Text entries.
         */
        $order = 0;

        foreach ($value['entries'] ?? [] as $entry) {
            TextEntry::fromInternalJson($entry, $item, ++$order);
        }
        foreach ($value['entriesHigherLevel'] ?? [] as $entry) {
            TextEntry::fromInternalJson($entry, $item, ++$order);
        }

        // Spell levels need special handling because the classes for each spell are stored in a separate file.
        try {
            $spellSources = FeToolsService::getClassesForSpell($item->spell->name);
        } catch (InvalidArgumentException | FileNotFoundException $e) {
            // We will just silently ignore this.
            $spellSources = [];
        }

        foreach ($spellSources as $className => $sources) {
            $sel = new SpellEditionLevel();
            $sel->spellEdition()->associate($item);
            $sel->level = $value['level'];

            // Get the character class.
            $characterClass = CharacterClass::query()->where('name', $className)->firstOrFail();
            $sel->entity()->associate($characterClass);
            $sel->save();

            // Add a reference for each source if we can determine the sourcebook from the shortName.
            foreach ($sources as $source) {
                $sourcebook = Source::query()->where('short_name', $source)->first();
                if (!empty($sourcebook)) {
                    $reference = new Reference();
                    $reference->edition()->associate($sourcebook->primaryEdition);
                    $reference->entity()->associate($sel);
                    $reference->save();
                }
            }
        }

        $item->save();
        return $item;
    }
}
