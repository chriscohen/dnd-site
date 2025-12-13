<?php

declare(strict_types=1);

namespace App\Models\AbilityScores;

use App\Enums\AbilityScoreType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use App\Models\Species\SpeciesEdition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?int $cha
 * @property ?int $con
 * @property ?int $dex
 * @property ?int $int
 * @property Collection<AbilityScoreModifier> $modifiers
 * @property SpeciesEdition $parent
 * @property ?int $str
 * @property ?int $wis
 */
class AbilityScoreModifierGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

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
        $output = [];

        foreach (['str', 'dex', 'con', 'int', 'wis', 'cha'] as $type) {
            if (!empty($this->{$type})) {
                $output[$type] = $this->{$type};
            }
        }

        return $output;
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->pa
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->parent()->associate($parent);

        foreach ($value as $type => $modifier) {
            $modifier = AbilityScoreModifier::fromInternalJson([
                'ability' => $type,
                'modifier' => $modifier,
            ], $item);
            $item->modifiers()->save($modifier);
        }

        $item->save();
        return $item;
    }

    public static function fromFeJson(array $value, ?ModelInterface $parent = null): ModelInterface
    {
        return static::fromInternalJson($value, $parent);
    }
}
