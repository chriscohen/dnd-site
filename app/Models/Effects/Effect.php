<?php

declare(strict_types=1);

namespace App\Models\Effects;

use App\Enums\Effects\EffectType;
use App\Models\AbstractModel;
use App\Models\Damage\DamageInstance;
use App\Models\ModelInterface;
use App\Models\Spells\SpellEdition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Describes something that happens to a creature or object, such as taking damage, or applying a condition.
 *
 * @param ?SpellEdition $owner
 * @param Collection<DamageInstance> $instances
 * @param EffectType $type
 */
class Effect extends AbstractModel
{
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

    public static function fromInternalJson(int|array|string $value, ?ModelInterface $parent = null): static
    {
        return new static();
    }
}
