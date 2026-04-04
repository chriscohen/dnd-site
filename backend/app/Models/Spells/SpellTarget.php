<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\TargetType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?bool $all_in_area
 * @property int $quantity
 * @property TargetType $type
 */
class SpellTarget extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'type' => TargetType::class,
    ];

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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        throw new \Exception('Not implemented');
    }
}
