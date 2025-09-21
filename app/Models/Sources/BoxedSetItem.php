<?php

declare(strict_types=1);

namespace App\Models\Sources;

use App\Enums\JsonRenderMode;
use App\Enums\SourceContentType;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property ?SourceContentType $contentType
 * @property ?int $pages
 * @property SourceEdition $parent
 * @property int $quantity
 */
class BoxedSetItem extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'content_type' => SourceContentType::class,
    ];

    protected function contentType(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => SourceContentType::tryFrom($value ?? 0)?->toString() ?? null,
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'parent_id');
    }

    public function toArrayLong(): array
    {
        return [
            'content_type' => $this->contentType,
            'pages' => $this->pages,
            'parent' => $this->parent->toArray(JsonRenderMode::SHORT, []),
            'quantity' => $this->quantity,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }
}
