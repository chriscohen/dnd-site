<?php

declare(strict_types=1);

namespace App\Models\Magic;

use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\Media;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 *
 * @property ?string $description
 * @property Media $image
 * @property ?MagicSchool $parent
 * @property string $parent_id
 * @property ?string $shortName
 */
class MagicSchool extends AbstractModel
{
    public $timestamps = false;
    public $incrementing = false;

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
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

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->name = $value['name'];
        $item->shortName = $value['shortName'] ?? null;
        $item->description = $value['description'] ?? null;

        if (empty($value['parent'])) {
            // If there's no parent, it's a base school, which means there's an image.
            $media = Media::fromInternalJson([
                'filename' => '/magic-schools/' . $value['id'],
            ]);
            $item->image()->associate($media);
        } else {
            // If there's a parent, it won't have an image.
            $item->parent_id = $value['parent'];
        }

        $item->save();
        return $item;
    }
}
