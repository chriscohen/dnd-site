<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?int $page_from
 * @property ?int $page_to
 */
class Reference extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'source_edition_id');
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
