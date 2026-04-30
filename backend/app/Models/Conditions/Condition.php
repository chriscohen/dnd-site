<?php

declare(strict_types=1);

namespace App\Models\Conditions;

use App\Enums\Conditions\ConditionType;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 * @property ConditionType $type
 *
 * @property Collection<ConditionEdition> $editions
 */
class Condition extends AbstractModel
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'type' => ConditionType::class,
        ];
    }

    public function editions(): HasMany
    {
        return $this->hasMany(ConditionEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->rules)->toArray($this->renderMode, $this->excluded),
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

    public function toArrayTeaser(): array
    {
        return [
            'editions' => ModelCollection::make($this->rules)->toArray($this->renderMode, $this->excluded),
        ];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = Uuid::fromString($value['id']);
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);
        $item->type = ConditionType::STATUS_CONDITION;
        $item->save();

        foreach ($value['editions'] ?? [] as $edition) {
            $edition = ConditionEdition::fromInternalJson($edition, $item);
            $item->editions()->save($edition);
        }

        $item->save();
        return $item;
    }
}
