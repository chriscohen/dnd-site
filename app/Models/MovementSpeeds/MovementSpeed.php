<?php

declare(strict_types=1);

namespace App\Models\MovementSpeeds;

use App\Enums\Movement\MovementType;
use App\Models\AbstractModel;
use App\Models\Actors\ActorType;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property bool $can_hover
 * @property ActorType|CreatureTypeEdition $parent
 * @property MovementType $type
 * @property int $value
 */
class MovementSpeed extends AbstractModel
{
    public $timestamps = false;

    public $casts = [
        'can_hover' => 'boolean',
        'type' => MovementType::class,
    ];

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'canHover' => $this->type == MovementType::FLY ? $this->can_hover : false,
            'group_id' => $this->group->id,
            'type' => $this->type,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    /**
     * @param array{
     *     canHover: ?bool,
     *     type: string,
     *     value: int
     * } $value
     */
    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->parent()->associate($parent);
        $item->type = MovementType::tryFromString($value['type'])
            ?? throw new \InvalidArgumentException('Invalid movement type: ' . $value['type']);
        $item->value = $value['value'];

        if (!empty($value['canHover'])) {
            $item->can_hover = $value['canHover'];
        }

        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }
}
