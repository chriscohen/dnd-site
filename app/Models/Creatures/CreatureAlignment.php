<?php

declare(strict_types=1);

namespace App\Models\Creatures;

use App\Castables\AsAlignment;
use App\Enums\Alignment\AlignmentGoodEvil;
use App\Enums\Alignment\AlignmentLawChaos;
use App\Models\AbstractModel;
use App\Models\Alignment\Alignment;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

/**
 * @param Alignment $alignment
 * @param CreatureEdition $creatureEdition
 */
class CreatureAlignment extends AbstractModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'alignment' => AsAlignment::class,
        ];
    }

    public function creatureEdition(): BelongsTo
    {
        return $this->belongsTo(CreatureEdition::class, 'creature_edition_id');
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureEdition()->associate($parent);

        if (count($value) === 1) {
            // We only have one alignment value, so...
            if ($value[0] === 'N') {
                // Neutral.
                $item->alignment = new Alignment(AlignmentLawChaos::NEUTRAL, AlignmentGoodEvil::NEUTRAL);
            } elseif ($value[0] === 'A') {
                $item->alignment = new Alignment(AlignmentLawChaos::ANY, AlignmentGoodEvil::ANY);
            } elseif ($value[0] === 'U') {
                // This creature is unaligned.
                $item->alignment = new Alignment();
            }
        } else {
            // Otherwise we're expecting two single-letter strings in the 'alignment' key.
            $lawChaos = AlignmentLawChaos::tryFromString($value[0]) ??
                throw new InvalidArgumentException("Could not parse alignment.");
            $goodEvil = AlignmentGoodEvil::tryFromString($value[1]) ??
                throw new InvalidArgumentException("Could not parse alignment.");
            $item->alignment = new Alignment($lawChaos, $goodEvil);
        }

        $item->save();
        return $item;
    }
}
