<?php

declare(strict_types=1);

namespace App\Models\Sources;

use App\Enums\SourcebookType;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Source $source
 * @property Uuid $source_id
 * @property SourcebookType $sourcebook_type
 */
class SourceSourcebookType extends AbstractModel
{
    public $timestamps = false;

    public $casts = [
        'sourcebook_type' => SourcebookType::class,
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function sourcebookType(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => SourcebookType::tryFrom($value)->toString(),
        );
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
}
