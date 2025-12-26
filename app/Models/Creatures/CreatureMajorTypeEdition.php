<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 *
 * @property ?string $alternate_name  Used if this edition had a different name than 5e.
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureMajorType()->associate($parent);
        $item->alternate_name = $value['alternateName'] ?? null;
        $item->description = $value['description'];
        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);
        $item->save();
        return $item;
    }

    public static function generate(ModelInterface $parent = null): static
    {
        $faker = static::getFaker();
        $item = new static();
        $item->creatureMajorType()->associate($parent);
        $item->description = $faker->sentence();
        $item->game_edition = GameEdition::FIFTH;
        $item->save();
        return $item;
    }
}
