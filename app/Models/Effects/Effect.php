<?php

declare(strict_types=1);

namespace App\Models\Effects;

use App\Enums\Effects\EffectType;
use App\Models\AbstractModel;
use App\Models\Damage\DamageInstance;
use App\Models\ModelInterface;
use App\Models\Spells\SpellEdition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Describes something that happens to a creature or object, such as taking damage, or applying a condition.
 *
 * @property string $id
 *
 * @property SpellEdition $owner
 * @property Collection<DamageInstance> $instances
 * @property EffectType $type
 */
class Effect extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'type' => EffectType::class,
        ];
    }

    public function instances(): HasMany
    {
        return $this->hasMany(DamageInstance::class);
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->owner()->associate($parent);
        $item->type = !empty($value['type']) ? EffectType::tryFromString($value['type']) : EffectType::DAMAGE;
        $item->save();

        foreach ($value as $damageInstance) {
            $instance = DamageInstance::fromInternalJson($damageInstance, $item);
            $item->instances()->save($instance);
        }

        $item->save();
        return $item;
    }
}
