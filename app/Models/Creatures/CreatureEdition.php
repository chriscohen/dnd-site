<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\AbilityScoreType;
use App\Enums\Conditions\ConditionInstanceType;
use App\Enums\Creatures\CreatureSizeUnit;
use App\Enums\Damage\DamageType;
use App\Enums\GameEdition;
use App\Enums\Movement\MovementType;
use App\Enums\SenseType;
use App\Enums\SkillMasteryLevel;
use App\Exceptions\RecordNotFoundException;
use App\Models\AbilityScores\AbilityScore;
use App\Models\AbilityScores\AbilityScoreModifierGroup;
use App\Models\AbstractModel;
use App\Models\ArmorClass\ArmorClass;
use App\Models\Conditions\ConditionInstance;
use App\Models\Dice\DiceFormula;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\MovementSpeeds\MovementSpeed;
use App\Models\Reference;
use App\Models\Skills\Skill;
use App\Models\Skills\SkillInstance;
use App\Models\Sources\Source;
use App\Models\Conditions\ConditionEdition;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @property string $id
 *
 * @property ?AbilityScoreModifierGroup $abilityScoreModifiers
 * @property Collection<AbilityScore> $abilities
 * @property Collection<CreatureAge> $ages
 * @property Collection<CreatureAlignment> $alignment
 * @property Collection<ArmorClass> $armorClass
 * @property ?float $challenge_rating
 * @property Collection<ConditionInstance> $conditionImmunities
 * @property Collection<ConditionInstance> $conditionInstances
 * @property Creature $creature
 * @property Collection<ConditionInstance> $damageImmunities
 * @property Collection<ConditionInstance> $damageResistances
 * @property Collection<ConditionInstance> $damageVulnerabilities
 * @property ?CreatureSense $darkvision
 * @property GameEdition $game_edition
 * @property bool $has_fixed_proficiency_bonus
 * @property ?int $height
 * @property ?DiceFormula $height_modifier
 * @property ?CreatureHitPoints $hitPoints
 * @property bool $is_playable
 * @property ?int $lair_xp
 * @property Collection<MovementSpeed> $movementSpeeds
 * @property int $passivePerception
 * @property ?int $proficiency_bonus
 * @property int $proficiencyBonus
 * @property Collection<Reference> $references
 * @property Collection<CreatureSense> $senses
 * @property ?Collection<CreatureSizeUnit> $sizes
 * @property Collection<SkillInstance> $skills
 * @property ?Collection<Tag> $tags
 * @property ?CreatureSense $truesight
 * @property ?CreatureType $type
 * @property ?int $weight
 * @property ?DiceFormula $weight_modifier
 *
 * @property AbilityScore $str
 * @property AbilityScore $dex
 * @property AbilityScore $con
 * @property AbilityScore $int
 * @property AbilityScore $wis
 * @property AbilityScore $cha
 */
class CreatureEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();
    }

    protected function casts(): array
    {
        return [
            'condition_immunities' => 'collection',
            'damage_immunities' => 'collection',
            'damage_resistances' => 'collection',
            'game_edition' => GameEdition::class,
            'has_fixed_proficiency_bonus' => 'boolean',
            'height_modifier' => DiceFormula::class,
            'is_playable' => 'boolean',
            'sizes' => AsEnumCollection::of(CreatureSizeUnit::class),
            'weight_modifier' => DiceFormula::class,
        ];
    }

    public function abilities(): MorphMany
    {
        return $this->morphMany(AbilityScore::class, 'parent');
    }

    public function abilityScoreModifiers(): MorphOne
    {
        return $this->morphOne(AbilityScoreModifierGroup::class, 'parent');
    }

    public function alignment(): HasMany
    {
        return $this->hasMany(CreatureAlignment::class);
    }

    public function ages(): HasMany
    {
        return $this->hasMany(CreatureAge::class);
    }

    public function armorClass(): HasMany
    {
        return $this->hasMany(ArmorClass::class, 'creature_edition_id');
    }

    public function canHover(): bool
    {
        return $this->movementSpeeds->firstWhere('type', MovementType::FLY)?->can_hover ?? false;
    }

    public function cha(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->abilities->firstWhere('type', AbilityScoreType::CHA)
        );
    }

    public function con(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->abilities->firstWhere('type', AbilityScoreType::CON)
        );
    }

    public function conditionImmunities(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->conditionInstances->where('type', ConditionInstanceType::STATUS_IMMUNITY)
        );
    }

    public function conditionInstances(): MorphMany
    {
        return $this->morphMany(ConditionInstance::class, 'entity');
    }

    public function creature(): BelongsTo
    {
        return $this->belongsTo(Creature::class);
    }

    public function darkvision(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->senses->find('type', SenseType::DARKVISION)
        );
    }

    public function damageImmunities(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->conditionInstances->where('type', ConditionInstanceType::DAMAGE_IMMUNITY)
        );
    }

    public function damageResistances(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->conditionInstances->where('type', ConditionInstanceType::DAMAGE_RESISTANCE)
        );
    }

    public function damageVulnerabilities(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->conditionInstances->where('type', ConditionInstanceType::DAMAGE_VULNERABILITY)
        );
    }

    public function dex(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->abilities->firstWhere('type', AbilityScoreType::DEX)
        );
    }

    public function getSkill(string|Skill $skill): ?SkillInstance
    {
        if (is_string($skill)) {
            $skill = Skill::query()->where('name', $skill)->first();
        }

        $skillEdition = $skill->editions->firstWhere('game_edition', $this->game_edition);
        /** @var SkillInstance $instance */
        $instance = $this->skills->firstWhere('skill_edition_id', $skillEdition->id);
        return $instance;
    }

    public function getSkillModifier(string|Skill $skill): int
    {
        if (is_string($skill)) {
            $skill = Skill::query()->where('name', $skill)->firstOrFail();
        }
        $skillEdition = $skill->editions->firstWhere('game_edition', $this->game_edition);
        $ability = $skillEdition->related_ability;
        $modifier = $this->abilities->firstWhere('type', $ability)->modifier;

        if ($this->hasSkillExpertise($skill)) {
            return $modifier + (2 * $this->proficiencyBonus);
        } elseif ($this->hasSkillProficiency($skill)) {
            return $modifier + $this->proficiencyBonus;
        } else {
            return $modifier;
        }
    }

    public function getSpeed(MovementType $type): ?MovementSpeed
    {
        /** @var MovementSpeed|null $output */
        $output = $this->movementSpeeds->firstWhere('type', $type->value);
        return $output;
    }

    public function hasResistance(DamageType $type): bool
    {
        return $this->damage_resistances->contains($type->value);
    }

    public function hasSkillExpertise(string|Skill $skill): bool
    {
        $skillInstance = $this->getSkill($skill);
        return !empty($skillInstance) && $skillInstance->mastery === SkillMasteryLevel::EXPERTISE;
    }

    public function hasSkillProficiency(string|Skill $skill): bool
    {
        $skillInstance = $this->getSkill($skill);

        return !empty($skillInstance) &&
            (
                $skillInstance->mastery === SkillMasteryLevel::PROFICIENT ||
                $skillInstance->mastery === SkillMasteryLevel::EXPERTISE
            );
    }

    public function hitPoints(): BelongsTo
    {
        return $this->belongsTo(CreatureHitPoints::class, 'creature_hit_points_id');
    }

    public function int(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->abilities->firstWhere('type', AbilityScoreType::INT)
        );
    }

    public function isImmuneTo(ConditionEdition | DamageType $type): bool
    {
        /** @var Collection<ConditionInstance> $immunities */
        $immunities = $type instanceof ConditionEdition ?
            $this->conditionImmunities :
            $this->damageImmunities;

        if ($type instanceof ConditionEdition) {
            return $immunities->contains('condition_edition_id', $type->id);
        } else {
            return $immunities->contains('damage_type', $type->value);
        }
    }

    public function movementSpeeds(): MorphMany
    {
        return $this->morphMany(MovementSpeed::class, 'parent');
    }

    public function passivePerception(): Attribute
    {
        return Attribute::make(
            get: fn () => 10 + $this->getSkillModifier('perception')
        );
    }

    public function proficiencyBonus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->has_fixed_proficiency_bonus && !empty($this->proficiency_bonus)) {
                    return $this->proficiency_bonus;
                } else {
                    return (int) (2 + floor(($this->challenge_rating ?? 0) / 4));
                }
            }
        );
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function senses(): HasMany
    {
        return $this->hasMany(CreatureSense::class);
    }

    public function skills(): MorphMany
    {
        return $this->morphMany(SkillInstance::class, 'entity');
    }

    public function str(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->abilities->firstWhere('type', AbilityScoreType::STR)
        );
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function truesight(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->senses->firstWhere('type', SenseType::TRUESIGHT)
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CreatureType::class, 'creature_type_id');
    }

    public function wis(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->abilities->firstWhere('type', AbilityScoreType::WIS)
        );
    }

    public function toArrayFull(): array
    {
        $output = [];

        if (!empty($this->abilityScoreModifiers)) {
            $output['abilityModifier'][] = $this->abilityScoreModifiers->toArray();
        }

        foreach (['str', 'dex', 'con', 'int', 'wis', 'cha'] as $ability) {
            if (!empty($this->{$ability})) {
                $output['ability'][$ability] = $this->{$ability}->toArray();
            }
        }

        foreach ($this->ages as $age) {
            $output['age'][$age->type->toString()] = $age->value;
        }
        $output['challengeRating'] = $this->challenge_rating;
        $output['conditionImmune'] = $this->condition_immunities;
        $output['hp'] = $this->hitPoints?->toArray($this->renderMode);
        $output['immune'] = $this->damage_immunities;
        $output['isPlayable'] = $this->is_playable;
        $output['proficiencyBonus'] = $this->proficiency_bonus;
        $output['resist'] = $this->damage_resistances;

        foreach ($this->senses as $sense) {
            $output['senses'][] = $sense->toArray();
        }

        $output['speed'] = $this->movementSpeeds?->toArray() ?? [];
        // TODO: come back to this.
        //$output['tags'] = ModelCollection::make($this->tags)->toArray();

        if (!empty($this->references)) {
            $output['references'] = ModelCollection::make($this->references)->toArray();
        }

        return $output;
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'size' => $this->sizes->toArray(),
            'creatureId' => $this->creature->id,
            'gameEdition' => $this->game_edition->toStringShort(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        // Assume it's the most recent edition, and if the reference given below is a source from 5e (2014) we'll
        // change the edition.
        $item->game_edition = GameEdition::FIFTH_REVISED;
        $item->creature()->associate($parent);

        $item->save();

        // Creature size.
        if (!empty($value['size'])) {
            // There's a weird case in the 5e.tools data for the "Verdan" in Acquisitions Incorporated where the size
            // is listed as "V". Verdan can be S or M size.
            if ($value['size'] === 'V' || $value['size'][0] === 'V') {
                $value['size'] = ['S', 'M'];
            }

            if (!is_array($value['size'])) {
                $value['size'] = [$value['size']];
            }
            foreach ($value['size'] as $size) {
                $sizeUnit = CreatureSizeUnit::tryFromString($size);
                $item->sizes()->save($sizeUnit);
            }
        }

        /**
         * Ability modifiers.
         */
        if (!empty($value['ability'])) {
            $modifierGroup = AbilityScoreModifierGroup::fromInternalJson($value['ability'], $item);
            $item->abilityScoreModifiers()->save($modifierGroup);
        }

        // Reference.
        if (!empty($value['source'])) {
            $reference = Reference::from5eJson([
                'source' => $value['source'],
                'page' => $value['page'] ?? null,
            ], $item);
            // Use the game edition of the sourcebook.
            $item->game_edition = GameEdition::tryFromString($reference->edition->source->game_edition);
        }

        // Movement speeds.
        if (!empty($value['speed'])) {
            $movementSpeedGroup = MovementSpeedGroup::fromInternalJson($value['speed'], $item);
            $item->movementSpeeds()->save($movementSpeedGroup);
        }

        $item->save();
        return $item;
    }

    /**
     * @param Creature $parent
     * @throws RecordNotFoundException
     */
    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        /**
         * Game Edition.
         */
        if (empty($value['source'])) {
            throw new \InvalidArgumentException('Creature edition must have a source.');
        }
        try {
            /** @var Source $source */
            $source = Source::query()->where('shortName', $value['source'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new \InvalidArgumentException('Creature edition source not found: ' . $value['source']);
        }

        // Try to infer the game edition from the sourcebook.
        $edition = GameEdition::tryFromString($source->game_edition) ??
            throw new \InvalidArgumentException("Could not infer game edition from sourcebook: {$source->name}");

        // Do we already have a CreatureEdition for this GameEdition? Use it, otherwise create a new one.
        $parent->refresh();
        $item = $parent->editions->firstWhere('game_edition', $edition) ?? new static();
        $item->game_edition = $edition;

        // In case we couldn't set an edition, assume 5th edition.
        if (empty($item->game_edition)) {
            $item->game_edition = GameEdition::FIFTH_REVISED;
        }
        if (empty($item->creature)) {
            $item->creature()->associate($parent);
        }
        $item->save();

        /**
         * Alignment.
         */
        $item->save();
        if (!empty($value['alignment'])) {
            if (empty($value['alignment'][0]['alignment'])) {
                // Sometimes we have just one alignment in an array, but sometimes there are multiple and each alignment
                // is its own array. If it's just one alignment, shift it to multiple-style with a single element.
                $old = $value['alignment'];
                $value['alignment'] = [];
                $value['alignment'][]['alignment'] = $old;
            }
            foreach ($value['alignment'] as $alignmentItem) {
                $alignment = CreatureAlignment::fromInternalJson($alignmentItem['alignment'], $item);
                $item->alignment()->save($alignment);
            }
        }

        /**
         * Creature type.
         */
        if (!empty($value['type']) && empty($item->type)) {
            try {
                $type = CreatureType::from5eJson($value['type'], $item);
                $item->type()->associate($type);
            } catch (ModelNotFoundException $e) {
                die("Could not find CreatureType.\n");
            }
        }

        // Check we don't already have this reference.
        if (!$item->references->contains('source.slug', $value['source'])) {
            $reference = Reference::from5eJson([
                'source' => $value['source'],
                'page' => $value['page'] ?? null,
            ], $item);
            $item->references()->save($reference);
        }

        /**
         * Challenge Rating.
         */
        if (!empty($value['cr'])) {
            if (is_array($value['cr'])) {
                $field = $value['cr']['cr'];
                $item->lair_xp = $value['cr']['xpLair'] ?? null;
            } else {
                $field = $value['cr'];
            }

            $item->challenge_rating = match ($field) {
                '1/8' => 0.125,
                '1/4' => 0.25,
                '1/2' => 0.5,
                default => $field
            };
        }

        /**
         * Sizes.
         */
        // In case "size" is not an array, turn it into one.
        if (is_null($item->sizes)) {
            $item->sizes = new SupportCollection();
        }

        if (!empty($value['size']) && !is_array($value['size'])) {
            $value['size'] = [$value['size']];
        }

        foreach ($value['size'] ?? [] as $size) {
            $sizeUnit = CreatureSizeUnit::tryFromString($size);

            if (!$item->sizes->contains($sizeUnit)) {
                $item->sizes->push($sizeUnit);
            }
        }

        /**
         * Ability scores.
         */
        foreach (['str', 'dex', 'con', 'int', 'wis', 'cha'] as $ability) {
            if (!empty($value[$ability]) && empty($item->{$ability})) {
                $abilityScore = AbilityScore::fromNumber(
                    (int) $value[$ability],
                    $ability,
                    $item,
                    // If the ability is listed in the "save" array, we will treat the creature as being proficient in
                    // that ability. We will ignore the actual modifiers ("+9", etc) because these are derived values.
                    !empty($value['save'][$ability])
                );
                $item->abilities()->save($abilityScore);
            }
        }

        /**
         * Armor class.
         *
         * 5e.tools can have multiple armor classes for a single creature.
         */
        if (!empty($value['ac'])) {
            // Remove the dexterity modifier from the armor class.
            $modifier = AbilityScore::getModifier((int) $value['dex']);

            // TODO - make this work somehow.
//            if (is_array($value['ac'])) {
//                $value['ac']['ac'] -= $modifier;
//            } else {
//                $value['ac'] -= $modifier;
//            }

            foreach ($value['ac'] as $acItem) {
                $ac = ArmorClass::from5eJson($acItem, $item);
                $item->armorClass()->save($ac);
            }
        }

        /**
         * Hit points.
         */
        if (!empty($value['hp']) && empty($item->hitPoints)) {
            $hpItem = CreatureHitPoints::from5eJson($value['hp'], $item);
            $item->hitPoints()->associate($hpItem);
        }

        /**
         * Movement speeds.
         */
        if (!empty($value['speed'])) {
            if (is_array($value['speed'])) {
                // Speed is an array of speeds, plus maybe the "canHover" key.
                $canHover = !empty($value['speed']['canHover']) && $value['speed']['canHover'] === true;

                foreach ($value['speed'] as $speedType => $speedItem) {
                    // Make sure speed type is valid, because sometimes we have a "canHover" key.
                    $movementSpeedType = MovementType::tryFromString($speedType);
                    if (empty($movementSpeedType)) {
                        continue;
                    }

                    // Do we already have a speed of this type?
                    if ($item->movementSpeeds->contains('type', $movementSpeedType)) {
                        continue;
                    }

                    // Sometimes $speedItem is not a number, it's another array.
                    $speedValue = is_array($speedItem) ? $speedItem['number'] : $speedItem;

                    if (mb_strtolower($speedType) === 'fly') {
                        // Special case for fly speeds - add canHover flag.
                        $movementSpeed = MovementSpeed::from5eJson([
                            'type' => 'fly',
                            'value' => $speedValue,
                            'canHover' => $canHover,
                        ], $item);
                    } else {
                        // A movement speed other than fly.
                        $movementSpeed = MovementSpeed::from5eJson([
                            'type' => $speedType,
                            'value' => $speedValue,
                        ], $item);
                    }

                    $item->movementSpeeds()->save($movementSpeed);
                }
            } elseif (!$item->movementSpeeds->contains('type', MovementType::WALK)) {
                // Speed is just a single number. Assume it's walking.
                $movementSpeed = MovementSpeed::from5eJson([
                    'type' => 'walk',
                    'value' => $value['speed']
                ], $item);
                $item->movementSpeeds()->save($movementSpeed);
            }
        }

        /**
         * Skills.
         */
        $item->save();
        $item->refresh();
        foreach ($value['skill'] ?? [] as $skillName => $bonus) {
            $skillInstance = SkillInstance::from5eJson([
                'skill' => $skillName,
                'bonus' => $bonus
            ], $item);
            $item->skills()->save($skillInstance);
        }
        $item->save();

        /**
         * Immunities and resistances.
         */
        foreach ($value['conditionImmune'] ?? [] as $conditionItem) {
            try {
                // Correct for a weird exception where "diseased" is called "disease" in the 5e.tools data. Even though
                // "diseased" is not a 5e condition.
                if ($conditionItem === 'disease') {
                    $conditionItem = 'diseased';
                }

                $instance = ConditionInstance::from5eJson([
                    'name' => $conditionItem,
                    'type' => ConditionInstanceType::STATUS_IMMUNITY
                ], $item);
                $item->conditionInstances()->save($instance);
            } catch (ModelNotFoundException $e) {
                print("[WARNING] Could not find StatusCondition: {$conditionItem}\n");
            }
        }

        foreach (['immune', 'resist', 'vulnerable'] as $damageType) {
            foreach ($value[$damageType] ?? [] as $damageTypeItem) {
                if (is_string($damageTypeItem)) {
                    // The condition is just a single string.
                    $instance = ConditionInstance::from5eJson([
                        'name' => $damageTypeItem,
                        'type' => ConditionInstanceType::tryFromString($damageType)
                    ], $item);
                } else {
                    // The condition is an object eg
                    // array{
                    //   resist: string[] <-- condition names here
                    //   note: ?string
                    //   cond: ?bool
                    foreach ($damageTypeItem[$damageType] ?? [] as $innerItem) {
                        $instance = ConditionInstance::from5eJson([
                            'name' => $innerItem,
                            'type' => ConditionInstanceType::tryFromString($damageType),
                            'note' => $damageTypeItem['note'] ?? null,
                            'nonmagical' => str_contains($damageTypeItem['note'], 'nonmagical')
                        ], $item);
                    }
                }
            }
        }

        /**
         * Senses.
         */
        foreach ($value['senses'] ?? [] as $senseItem) {
            $sense = CreatureSense::from5eJson($senseItem, $item);

            try {
                $item->senses()->save($sense);
            } catch (UniqueConstraintViolationException $e) {
                print "[WARNING] Multiple entries for sense {$senseItem}\n";
            }
        }
        // Sometimes the key is just in the $value array instead of a "senses" array.
        if (!empty($value['darkvision']) && empty($item->darkvision)) {
            $darkvision = CreatureSense::from5eJson([
                'type' => 'darkvision',
                'value' => (int) $value['darkvision'],
            ], $item);
            $item->senses()->save($darkvision);
        }

        /**
         * Height and Weight.
         */
        if (!empty($value['heightAndWeight'])) {
            $item->height = $value['heightAndWeight']['height'] ?? $value['heightAndWeight']['baseHeight'] ?? null;
            if (!empty($value['heightAndWeight']['heightMod'])) {
                $item->height_modifier = $value['heightAndWeight']['heightMod'];
            }
            $item->weight = $value['heightAndWeight']['weight'] ?? $value['heightAndWeight']['baseWeight'] ?? null;
            if (!empty($value['heightAndWeight']['weightMod'])) {
                $item->weight_modifier = $value['heightAndWeight']['weightMod'];
            }
        }

        /**
         * Ages.
         */
        foreach ($value['age'] ?? [] as $ageType => $ageItem) {
            try {
                $age = CreatureAge::from5eJson([
                    'type' => $ageType,
                    'value' => $ageItem,
                ], $item);

                $item->ages()->save($age);
            } catch (UniqueConstraintViolationException $e) {
                print "[WARNING] Multiple entries for age {$ageType}\n";
            }
        }

        $item->save();
        return $item;
    }

    public static function generate(ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creature()->associate($parent);

        /**
         * Type.
         */
        $type = CreatureType::generate($item);
        $item->type()->associate($type);

        $item->save();

        /**
         * Armor Class.
         */
        for ($i = 1; $i <= mt_rand(1, 3); $i++) {
            $ac = ArmorClass::generate($item);
            $item->armorClass()->save($ac);
        }

        $item->save();
        return $item;
    }
}
