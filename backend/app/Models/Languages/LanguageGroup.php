<?php

declare(strict_types=1);

namespace App\Models\Languages;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property Collection<Language> $languages
 */
class LanguageGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
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

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();

        $item->save();
        return $item;
    }
}
