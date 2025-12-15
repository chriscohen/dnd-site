<?php

declare(strict_types=1);

namespace App\Models\ArmorClass;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ArmorClass $armorClass
 */
class ArmorClassItem extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function armorClass(): BelongsTo
    {
        return $this->belongsTo(ArmorClass::class, 'armor_class_id');
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

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }
}
