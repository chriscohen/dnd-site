<?php

declare(strict_types=1);

namespace App\Models\AbilityScores;

use App\Enums\AbilityScoreType;
use App\Models\AbstractModel;
use App\Models\Creatures\CreatureEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?int $choice_count
 * @property ?string $choices
 * @property bool $has_choice
 * @property Collection<AbilityScoreModifier> $modifiers
 * @property CreatureEdition $parent
 *
 * @property ?int $str
 * @property ?int $dex
 * @property ?int $con
 * @property ?int $int
 * @property ?int $wis
 * @property ?int $cha
 */
class AbilityScoreModifierGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'has_choice' => 'boolean',
    ];

    public function cha(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->modifiers->firstWhere('ability_score', AbilityScoreType::CHA)?->modifier
        );
    }

    public function con(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->modifiers->firstWhere('ability_score', AbilityScoreType::CON)?->modifier
        );
    }

    public function dex(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->modifiers->firstWhere('ability_score', AbilityScoreType::DEX)?->modifier
        );
    }

    public function int(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->modifiers->firstWhere('ability_score', AbilityScoreType::INT)?->modifier
        );
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(AbilityScoreModifier::class);
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function str(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->modifiers->firstWhere('ability_score', AbilityScoreType::STR)?->modifier
        );
    }

    public function wis(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->modifiers->firstWhere('ability_score', AbilityScoreType::WIS)?->modifier
        );
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        $output = [];

        foreach ($this->modifiers as $modifier) {
            $output[mb_strtolower($modifier->ability_score->name)] = $modifier->value;
        }

        if ($this->has_choice) {
            $output['choose'] = [
                'from' => explode(',', $this->choices),
                'count' => $this->choice_count ?? 0,
            ];
        }

        return $output;
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->parent()->associate($parent);
        $item->save();

        foreach ($value as $modifiers) {
            // Handle each ability score key.
            foreach (['str', 'dex', 'con', 'int', 'wis', 'cha'] as $abilityName) {
                if (!empty($modifiers[$abilityName])) {
                    $abilityScoreModifier = AbilityScoreModifier::fromInternalJson([
                        'ability' => $abilityName,
                        'modifier' => $modifiers[$abilityName],
                    ], $item);
                    $item->modifiers()->save($abilityScoreModifier);
                }
            }

            // Handle "choose".
            if (!empty($modifiers['choose'])) {
                $item->has_choice = true;
                // The "count" is sometimes called "amount".
                $item->choice_count = !empty($modifiers['choose']['count']) ?
                    $modifiers['choose']['count'] :
                    (!empty($modifiers['choose']['amount']) ? $modifiers['choose']['amount'] : 1);
                $item->choices = mb_strtolower(implode(',', $modifiers['choose']['from']));
            }
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
