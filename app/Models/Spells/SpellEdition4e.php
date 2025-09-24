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
 * @property ?Attribute $attack_attribute
 * @property ?SavingThrowType $attack_save
 * @property ?SpellFrequency $frequency
 * @property SpellEdition $spellEdition
 * @property Collection<SpellTrait> $spellTraits
 * @property SpellType4e $type
 */
class SpellEdition4e extends AbstractModel
{
    use HasUuids;

    public $table = 'spell_editions_4e';
    public $timestamps = false;

    public $casts = [
        'attack_attribute' => Attribute::class,
        'attack_save' => SavingThrowType::class,
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
            'spell_editions_4e_traits',
            'spell_trait_id',
            'spell_edition_4e_id'
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
}
