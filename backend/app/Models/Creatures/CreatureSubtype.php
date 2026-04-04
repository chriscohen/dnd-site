<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property ?string $description
 * @property Collection<GameEdition> $editions
 */
class CreatureSubtype extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'editions' => 'collection',
        ];
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
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->description = $value['description'] ?? null;
        $item->editions = $value['editions'] ?? [];
        $item->save();
        return $item;
    }
}
