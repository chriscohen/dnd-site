<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\Attribute;
use App\Enums\SavingThrows\SavingThrowType;
use App\Enums\Spells\SpellFrequency;
use App\Enums\Spells\SpellType4e;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?Attribute $attackAttribute
 * @property ?SavingThrowType $attackSave
 * @property ?SpellFrequency $frequency
 * @property SpellEdition $spellEdition
 * @property Collection<SpellTrait> $spellTraits
 * @property SpellType4e $type
 */
class SpellEdition4e extends AbstractModel
{
    use HasUuids;

    public $table = 'spellEditions4e';
    public $timestamps = false;

    public $casts = [
        'attackAttribute' => Attribute::class,
        'attackSave' => SavingThrowType::class,
        'frequency' => SpellFrequency::class,
        'type' => SpellType4e::class,
    ];

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(SpellEdition::class);
    }

    public function spellTraits(): BelongsToMany
    {
        return $this->belongsToMany(
            SpellTrait::class,
            'spellEditions4eTraits',
            'spellTraitId',
            'spellEdition4eId'
        );
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

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
