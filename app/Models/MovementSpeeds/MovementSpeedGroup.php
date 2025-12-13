<?php

declare(strict_types=1);

namespace App\Models\MovementSpeeds;

use App\Models\AbstractModel;
use App\Models\Actors\ActorTypeEdition;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
use App\Models\Species\SpeciesEdition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property ActorTypeEdition|SpeciesEdition $parent
 * @property Collection<MovementSpeed> $speeds
 */
class MovementSpeedGroup extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function burrow(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->speeds->firstWhere('type', 'burrow')?->speed
        );
    }

    public function canHover(): ?Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->fly?->canHover ?? false
        );
    }

    public function climb(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->speeds->firstWhere('type', 'climb')?->speed
        );
    }

    public function fly(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->speeds->firstWhere('type', 'fly')?->speed
        );
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function speeds(): HasMany
    {
        return $this->hasMany(MovementSpeed::class);
    }

    public function swim(): ?Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->speeds->firstWhere('type', 'swim')?->speed
        );
    }

    public function walk(): ?Attribute
    {
        return Attribute::make(
            get: fn(): ?int => $this->speeds->firstWhere('type', 'walk')?->speed
        );
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        $output = [];

        foreach (['burrow', 'climb', 'fly', 'swim', 'walk'] as $type) {
            if (!empty($this->{$type})) {
                $output[$type] = $this->{$type};
            }
        }

        if ($this->canHover) {
            $output['canHover'] = true;
        }

        return $output;
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->parent()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();

        foreach ($value as $type => $speed) {
            $movementSpeed = MovementSpeed::fromInternalJson([
                'type' => $type,
                'speed' => $speed
            ], $item);
            $item->speeds()->save($movementSpeed);
        }

        $item->save();
        return $item;
    }
}
