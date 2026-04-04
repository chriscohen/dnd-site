<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\AbilityScoreType as Attr;
use App\Enums\Prerequisites\CraftType;
use App\Enums\Prerequisites\KnowledgeType;
use App\Enums\Prerequisites\PrerequisiteType;
use App\Enums\Prerequisites\WeaponFocusType;
use App\Enums\SpellcasterType;
use App\Models\AbstractModel;
use App\Models\Creatures\CreatureType;
use App\Models\Feats\Feature;
use App\Models\Languages\Language;
use App\Models\ModelInterface;
use App\Models\Skills\Skill;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?CraftType $craft_type
 * @property ?KnowledgeType $knowledge_type
 * @property ?Language $language
 * @property PrerequisiteGroup $prerequisite
 * @property ?int $skill_ranks
 * @property string $value
 * @property ?WeaponFocusType $weapon_focus_type
 */
class PrerequisiteValue extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'craft_type' => CraftType::class,
        'knowledge_type' => KnowledgeType::class,
        'weapon_focus_type' => WeaponFocusType::class,
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(PrerequisiteGroup::class);
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'craft_type' => $this->craft_type?->toString(),
            'skill_ranks' => $this->skill_ranks,
            'value' => $this->value,
            'weapon_focus_type' => $this->weapon_focus_type?->toString(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    protected function validateAbilityScore(string $input): bool
    {
        [$ability, $value] = explode(':', $input);
        $attribute = Attr::tryFromString($ability);

        if (empty($attribute) || !is_numeric($value)) {
            throw new InvalidArgumentException('"' . $input . '" is not a valid prerequisite value.');
        }

        return true;
    }

    protected function validateValue(string $value): string
    {
        match ($this->prerequisite->type) {
            PrerequisiteType::ABILITY_SCORE => $this->validateAbilityScore($value),
            PrerequisiteType::FEAT => Feature::query()->where('slug', $value)->firstOrFail(),
            PrerequisiteType::SKILL => Skill::query()->where('slug', $value)->firstOrFail(),
            PrerequisiteType::CREATURE => CreatureType::query()->where('slug', $value)->firstOrFail(),
            PrerequisiteType::SPELLCASTER_TYPE => SpellcasterType::tryFromString($value, true),
            default => $x = 5,
        };

        return $value;
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => $this->validateValue($value),
        );
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->prerequisite()->associate($parent);

        if (is_array($value)) {
            $item->skill_ranks = $value['skillRanks'] ?? null;
            $item->value = $value['value'];

            if ($value['value'] == 'speak language') {
                $language = Language::query()->where('id', $value['language'])->firstOrFail();
                $item->language()->associate($language);
            }

            if (!empty($value['craftType'])) {
                $item->craft_type = CraftType::tryFromString($value['craftType'], true);
            }
            if (!empty($value['knowledgeType'])) {
                $item->knowledge_type = KnowledgeType::tryFromString($value['knowledgeType'], true);
            }
            if (!empty($valueData['weaponFocusType'])) {
                $item->weapon_focus_type = WeaponFocusType::tryFromString(
                    $valueData['weaponFocusType'],
                    true
                );
            }
        } else {
            $item->value = $value;
        }

        $item->save();
        return $item;
    }
}
