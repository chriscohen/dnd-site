<?php

declare(strict_types=1);

namespace App\Models\MovementSpeeds;

use App\Enums\Movement\MovementType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property bool $can_hover
 * @property MovementSpeedGroup $group
 * @property int $speed
 * @property MovementType $type
 */
class MovementSpeed extends AbstractModel
{
    public $timestamps = false;

    public $casts = [
        'type' => MovementType::class,
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(MovementSpeedGroup::class);
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
     *     type: string,
     *     speed: int
     * } $value
     */
    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->group()->associate($parent);
        $item->type = MovementType::tryFromString($value['type']);
        $item->speed = $value['speed'];
        $item->save();
        return $item;
    }

    public static function from5eJson(array|string $value, ModelInterface $parent = null): ModelInterface
    {
        return static::fromInternalJson($value, $parent);
    }
}
