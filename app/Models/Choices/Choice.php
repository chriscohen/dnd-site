<?php

declare(strict_types=1);

namespace App\Models\Choices;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property int $count
 * @property Collection<ChoiceOption> $options
 */
class Choice extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function options(): HasMany
    {
        return $this->hasMany(ChoiceOption::class);
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
