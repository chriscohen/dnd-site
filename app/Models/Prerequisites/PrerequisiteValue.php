<?php

declare(strict_types=1);

namespace App\Models\Prerequisites;

use App\Enums\Prerequisites\CraftType;
use App\Enums\Prerequisites\KnowledgeType;
use App\Enums\Prerequisites\PrerequisiteType;
use App\Enums\Prerequisites\WeaponFocusType;
use App\Enums\SpellcasterType;
use App\Models\AbstractModel;
use App\Models\Feats\Feat;
use App\Models\Language;
use App\Models\Skills\Skill;
use App\Models\Species;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;
use App\Enums\Attribute as Attr;
use InvalidArgumentException;

/**
 * @property Uuid $id
 *
 * @property ?CraftType $craft_type
 * @property ?KnowledgeType $knowledge_type
 * @property ?Language $language
 * @property Prerequisite $prerequisite
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
        return $this->belongsTo(Prerequisite::class);
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
            PrerequisiteType::FEAT => Feat::query()->where('slug', $value)->firstOrFail(),
            PrerequisiteType::SKILL => Skill::query()->where('slug', $value)->firstOrFail(),
            PrerequisiteType::SPECIES => Species::query()->where('slug', $value)->firstOrFail(),
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

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
