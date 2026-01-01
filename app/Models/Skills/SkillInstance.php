<?php

declare(strict_types=1);

namespace App\Models\Skills;

use App\Enums\SkillMasteryLevel;
use App\Models\AbilityScores\AbilityScore;
use App\Models\AbstractModel;
use App\Models\Actors\ActorType;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents a skill possessed by a creature.
 *
 * @property ActorType|CreatureTypeEdition $entity
 * @property SkillMasteryLevel $mastery
 * @property SkillEdition $skillEdition
 */
class SkillInstance extends AbstractModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'mastery' => SkillMasteryLevel::class,
        ];
    }

    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }

    public function skillEdition(): BelongsTo
    {
        return $this->belongsTo(SkillEdition::class, 'skill_edition_id');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    /**
     * @param array{
     *     skill: string,
     *     bonus: int,
     * } $value
     * @param CreatureTypeEdition|null $parent
     */
    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $skillName = str_replace(' ', '-', $value['skill']);
        $skill = Skill::query()->where('slug', $skillName)->firstOrFail();
        $skillEdition = $skill->editions->firstWhere('game_edition', $parent->game_edition);
        $ability = $skillEdition->related_ability;

        // Take just the number from the bonus.
        $value['bonus'] = str_replace('+', '', $value['bonus']);

        $item = new static();
        $item->entity()->associate($parent);
        $item->skillEdition()->associate($skillEdition);

        // Get the ability modifier for this skill, from the parent. Eg, if it's Arcana, the ability is INT, so we will
        // use the ->int->modifier property.
        $methodName = mb_strtolower($ability->toStringShort());
        /** @var AbilityScore $abilityScore */
        $abilityScore = $parent->{$methodName};
        $abilityModifier = $abilityScore->modifier;
        $prof = $parent->proficiencyBonus;

        if ($value['bonus'] == $abilityModifier + $prof) {
            // The bonus is modifier + prof, so this is a proficient skill (mastery).
            $item->mastery = SkillMasteryLevel::PROFICIENT;
        } elseif ($value['bonus'] == $abilityModifier + (2 * $prof)) {
            $item->mastery = SkillMasteryLevel::EXPERTISE;
        }
        // By default, the bonus is the ability modifier, so this is a natural skill (no mastery).

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
