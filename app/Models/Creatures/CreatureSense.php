<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Enums\DistanceUnit;
use App\Enums\SenseType;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property CreatureEdition $creatureEdition
 * @property ?string $description
 * @property SenseType $type
 * @property ?int $range
 * @property DistanceUnit $distance_unit
 */
class CreatureSense extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'type' => SenseType::class,
            'distance_unit' => DistanceUnit::class,
        ];
    }

    public function creatureEdition(): BelongsTo
    {
        return $this->belongsTo(CreatureEdition::class, 'creature_edition_id');
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
        return new static();
    }

    /**
     * @param string $value  A string like "tremorsense 60 ft., passive Perception 10"
     */
    public static function fromString(string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();

        // Try to extract a description which is the part after a semicolon.
        list($senseName, $description) = explode(';', $value, 2);
        $item->description = !empty($$description) ? trim($description) : null;

        // Try to extract the sense, range, and unit.
        list($senseType, $range, $unit) = explode(' ', trim($senseName));

        // Sense type.
        if (!empty($senseType)) {
            $senseItem = SenseType::tryFromString($senseType);

            if (!empty($senseItem)) {
                $item->type = $senseItem;
            } else {
                die('Sense type not recognized: ' . $senseType . "\n");
            }
        }
        $item->range = $range;
        $item->distance_unit = DistanceUnit::tryFromString($unit) ?? die('Unit not recognized: ' . $unit . "\n");

        $item->save();
        return $item;
    }

    /**
     * @param  array{
     *     'type': string,
     *     'value': int,
     * }|string  $value
     */
    public static function from5eJson(array|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureEdition()->associate($parent);

        list($senseType, $range) = explode(' ', trim($value), 2);

        $senseItem = SenseType::tryFromString(mb_strtolower($senseType));
        $item->type = $senseItem;

        list($number, $unit) = explode(' ', trim($range), 2);
        $item->range = (int) $number;
        $item->distance_unit = DistanceUnit::tryFromString($unit);

        $item->save();
        return $item;
    }
}
