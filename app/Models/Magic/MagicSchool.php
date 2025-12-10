<?php

declare(strict_types=1);

namespace App\Models\Magic;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 *
 * @property ?string $description
 * @property Media $image
 * @property string $imageId
 * @property ?MagicSchool $parent
 * @property string $parentId
 * @property ?string $shortName
 */
class MagicSchool extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'imageId');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MagicSchool::class, 'parent_id');
    }

    public function toArrayFull(): array
    {
        return [
            'description' => $this->description,
            'image' => $this->image?->toArray($this->renderMode),
            'parent' => $this->parent?->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortName' => $this->shortName,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array $value): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->shortName = $value['shortName'] ?? null;

        $item->description = $value['description'] ?? null;
        $item->parentId = $value['parentId'] ?? null;

        if (!empty($value['image'])) {
            $image = Media::query()->where('id', $value['image'])->firstOrFail();
            $item->image()->associate($image);
        }

        return $item;
    }
}
