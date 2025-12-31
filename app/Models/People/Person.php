<?php

declare(strict_types=1);

namespace App\Models\People;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property ?string $artstation
 * @property Collection<BookCredit> $credits
 * @property string $first_name
 * @property ?string $initials
 * @property ?string $instagram
 * @property string $last_name
 * @property ?string $middle_names
 * @property ?string $twitter
 * @property ?string $youtube
 */
class Person extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'people';

    public function credits(): HasMany
    {
        return $this->hasMany(BookCredit::class);
    }

    public function toArrayFull(): array
    {
        return [
            'id' => $this->id,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'youtube' => $this->youtube,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'slug' => $this->slug,
            'firstName' => $this->first_name,
            'initials' => empty($this->initials) ? null : str_split($this->initials),
            'lastName' => $this->last_name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(int|array|string $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->first_name = $value['firstName'];
        $item->last_name = $value['lastName'];
        $item->middle_names = $value['middleNames'] ?? null;
        $item->slug = $value['slug'] ?? (
        empty($value['initials']) ?
            Str::slug(implode(' ', [$item->first_name, $item->last_name])) :
            Str::slug($item->first_name . ' ' . $item->last_name)
        );

        if (!empty($value['initials'])) {
            $item->initials = implode('', $value['initials']);
        }

        foreach (['artstation', 'instagram', 'twitter', 'youtube'] as $fieldName) {
            if (!empty($value[$fieldName])) {
                $item->{$fieldName} = $value[$fieldName];
            }
        }
        $item->save();
        return $item;
    }
}
