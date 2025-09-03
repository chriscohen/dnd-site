<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SourceFormat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property SourceEdition $edition
 * @property Uuid $source_edition_id
 * @property SourceFormat $format
 */
class SourceEditionFormat extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'format' => SourceFormat::class,
    ];

    protected function format(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => SourceFormat::tryFrom($value)->toString(),
        );
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'source_edition_id');
    }
}
