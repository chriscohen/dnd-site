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
 * @property Uuid $creatureMajorTypeId
 * @property CreatureMajorType $creatureMajorType
 * @property string $description
 * @property GameEdition $gameEdition
 */
class CreatureMajorTypeEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'gameEdition' => GameEdition::class,
    ];

    public function creatureMajorType(): BelongsTo
    {
        return $this->belongsTo(CreatureMajorType::class, 'creatureMajorTypeId');
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => GameEdition::tryFrom($value)->toStringShort()
        );
    }

    public function toArrayFull(): array
    {
        return [
            'creatureMajorType' => $this->creatureMajorType->toArray($this->renderMode),
            'description' => $this->description,
            'gameEdition' => $this->gameEdition,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'creatureMajorTypeId' => $this->creatureMajorTypeId,
        ];
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
