<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Uuid $creature_major_type_id
 * @property CreatureMajorType $creatureMajorType
 * @property string $description
 * @property int $game_edition
 * @property string $gameEdition
 */
class CreatureMajorTypeEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
    ];

    public function creatureMajorType(): BelongsTo
    {
        return $this->belongsTo(CreatureMajorType::class, 'creature_major_type_id');
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => GameEdition::tryFrom($value)->toStringShort()
        );
    }

    public function toArrayLong(): array
    {
        return [
            'creature_major_type' => $this->creatureMajorType->toArray($this->renderMode, $this->excluded),
            'description' => $this->description,
            'game_edition' => $this->game_edition,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'creature_major_type_id' => $this->creature_major_type_id,
        ];
    }
}
