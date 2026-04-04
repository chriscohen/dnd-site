<?php

declare(strict_types=1);

namespace App\Models\Choices;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Choice $choice
 * @property ?string $description
 * @property ModelInterface $entity
 */
class ChoiceOption extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function choice(): BelongsTo
    {
        return $this->belongsTo(Choice::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public static function fromInternalJson(array|int|string $value, ModelInterface $parent = null): static
    {
        $item = new static();

        return $item;
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
