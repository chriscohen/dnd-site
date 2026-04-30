<?php

declare(strict_types=1);

namespace App\Models\Sources;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $header
 * @property SourceContents $sourceContents
 */
class SourceContentsHeader extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'source_contents_headers';

    public function sourceContents(): BelongsTo
    {
        return $this->belongsTo(SourceContents::class, 'source_contents_id');
    }

    public static function fromInternalJson(array|int|string $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->sourceContents()->associate($parent);
        $item->header = $value;
        $item->save();
        return $item;
    }

    public static function from5eJson(array|string|int $value, ModelInterface $parent = null): static
    {
        return static::fromInternalJson($value, $parent);
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'header' => $this->header,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
