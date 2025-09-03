<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $uuid
 * @property string $slug
 *
 * @property Media $logo
 * @property string $name
 * @property ?string $product_url
 * @property ?string $short_name
 * @property string $website
 */
class Company extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function getProductUrl(string $id): ?string
    {
        return empty($this->product_url) ?
            null :
            str_replace('{{id}}', $id, $this->product_url);
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }
}
