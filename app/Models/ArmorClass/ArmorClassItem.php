<?php

declare(strict_types=1);

namespace App\Models\ArmorClass;

use App\Enums\ArmorClass\ArmorClassSource;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ArmorClass $armorClass
 * @property ArmorClassSource $source_type
 * @property int $value
 */
class ArmorClassItem extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'source_type' => ArmorClassSource::class,
        ];
    }

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

    /**
     * @property array{
     *     ac: int,
     *     from: string[]
     * } $value
     */
    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->armorClass()->associate($parent);
        // The AC always includes the base of 10 so let's subtract that.
        $item->value = ((int) $value['ac']) - 10;

        $item->save();
        return $item;
    }
}
