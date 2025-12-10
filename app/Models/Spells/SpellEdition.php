<?php

namespace App\Models\Spells;

use App\Enums\Distance;
use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Enums\Rarity;
use App\Enums\Spells\MaterialComponentMode;
use App\Enums\Spells\SpellComponentType;
use App\Enums\Spells\SpellFrequency;
use App\Enums\TimeUnit;
use App\Models\AbstractModel;
use App\Models\Area;
use App\Models\DamageInstance;
use App\Models\Duration;
use App\Models\Feats\Feat;
use App\Models\Magic\MagicDomain;
use App\Models\Magic\MagicSchool;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\Range;
use App\Models\Reference;
use App\Models\SavingThrow;
use App\Models\Sources\Source;
use App\Models\Target;
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
 * @property int $castingTimeNumber
 * @property TimeUnit $castingTimeUnit
 * @property Collection<DamageInstance> $damageInstances
 * @property string $description
 * @property Collection<MagicDomain> $domains
 * @property Duration $duration
 * @property ?Feat $feat
 * @property ?string $focus
 * @property GameEdition $gameEdition
 * @property ?bool $hasSpellResistance
 * @property string $higherLevel
 * @property bool $is_default
 * @property Collection<SpellEditionLevel> $levels
 * @property ?MaterialComponentMode $materialComponentMode
 * @property Collection<SpellMaterialComponent> $materialComponents
 * @property ?Range $range
 * @property ?Uuid $rangeId
 * @property Rarity $rarity
 * @property Collection<Reference> $references
 * @property ?SavingThrow $savingThrow
 * @property MagicSchool $school
 * @property Spell $spell
 * @property string $spellComponents
 * @property ?SpellEdition4e $spellEdition4e
 * @property Uuid $spellId
 * @property Collection<Target> $targets
 */
class SpellEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'castingTimeUnit' => TimeUnit::class,
        'frequency' => SpellFrequency::class,
        'gameEdition' => GameEdition::class,
        'hasSpellResistance' => 'bool',
        'isDefault' => 'bool',
        'materialComponentMode' => MaterialComponentMode::class,
        'rangeUnit' => Distance::class,
        'rarity' => Rarity::class,
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'areaId');
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

    public function feat(): BelongsTo
    {
        return $this->belongsTo(Feat::class, 'featId');
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

        foreach ($this->levels as $level) {
            if ($level->level < $lowest) {
                $lowest = $level->level;
            }
        }

        return $lowest;
    }

    public function hasComponent(SpellComponentType $componentType): bool
    {
        return str_contains($this->spellComponents, $componentType->value);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(SpellEditionLevel::class, 'spell_edition_id');
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
        return $this->belongsTo(MagicSchool::class, 'magicSchoolId');
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

    public function toArrayFull(): array
    {
        return [
            'area' => $this->area?->toArray($this->renderMode) ?? null,
            'castingTime' => $this->castingTimeUnit->format($this->castingTimeNumber),
            'damageInstances' => ModelCollection::make($this->damageInstances)
                ->toArray(),
            'description' => $this->description,
            'domains' => ModelCollection::make($this->domains)->toArray($this->renderMode),
            'duration' => $this->duration->toArray($this->renderMode),
            'focus' => $this->focus,
            'hasSavingThrow' => $this->has_saving_throw,
            'hasSpellResistance' => $this->hasSpellResistance,
            'higherLevel' => $this->higherLevel,
            'isDefault' => $this->is_default,
            'levels' => ModelCollection::make($this->levels)->toArray(),
            'lowestLevel' => $this->getLowestLevel(),
            'materialComponentMode' => $this->materialComponentMode,
            'materialComponents' => ModelCollection::make($this->materialComponents)->toArray($this->renderMode),
            'range' => $this->range?->toArray($this->renderMode),
            'rarity' => $this->rarity->toString(),
            'references' => ModelCollection::make($this->references)->toArray(JsonRenderMode::TEASER),
            'savingThrow' => $this->savingThrow?->toArray($this->renderMode),
            'school' => $this->school?->toArray($this->renderMode) ?? null,
            'spellComponents' => $this->spellComponents,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'spellId' => $this->spellId,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'gameEdition' => $this->gameEdition,
        ];
    }


    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();

        return $item;
    }

    public static function fromFeJson(array $value, ?Spell $spell = null): self
    {
        $item = new static();
        $item->id = Uuid::uuid4();
        $item->name = $value['name'];
        $item->gameEdition = GameEdition::FIFTH_REVISED;

        if (!empty($spell)) {
            $item->spell()->associate($spell);
        }

        // Description.
        if (!empty($value['entries'])) {
            $item->description = '';

            foreach ($value['entries'] as $entry) {
                $item->description .= $entry['text'] . "\n";
            }
        }
        // At Higher Levels...
        if (is_array($value['entriesHigherLevel'])) {
            $item->higherLevel = '';

            foreach ($value['entriesHigherLevel'] as $higherLevelEntry) {
                if (is_array($higherLevelEntry['entries'])) {
                    foreach ($higherLevelEntry['entries'] as $entry) {
                        $item->higherLevel .= $entry . "\n";
                    }
                }
            }
        }

        // Magic school.
        if (!empty($value['school'])) {
            $school = MagicSchool::query()->where('shortName', mb_strtoupper($value['school']))->firstOrFail();
            $item->school()->associate($school);
        }

        // References.
        if (!empty($value['source'])) {
            $source = Source::query()->where('shortName', $value['source'])->firstOrFail();

        }

        return $item;
    }
}
