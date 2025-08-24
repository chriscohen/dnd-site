<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PublicationType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property ?string $logo
 * @property string $name
 * @property Company $publisher
 * @property Uuid $publisher_id
 * @property PublicationType $publication_type
 * @property string $short_name
 */
class CampaignSetting extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'publication_type' => PublicationType::class,
    ];

    public function publicationType(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => PublicationType::tryFrom($value)->toString(),
        );
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'publisher_id');
    }
}
