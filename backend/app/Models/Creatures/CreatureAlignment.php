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
 * @param ?Alignment $alignment
 * @param CreatureTypeEdition $creatureTypeEdition
 * @param ?string $description
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

    public function creatureTypeEdition(): BelongsTo
    {
        return $this->belongsTo(CreatureTypeEdition::class, 'creature_type_edition_id');
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromInternalJson(array|int|string $value, ?ModelInterface $parent = null): static
    {
        $item = new static();
        $item->creatureTypeEdition()->associate($parent);

        if (!empty($value['special'])) {
            // Sometimes there's some special alignment description.
            $item->description = $value['special'];
        }
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
            // We allow this to "fall through" with no "else" because if there's ONLY special text, it won't fail.
        } else {
            // Otherwise we're expecting two single-letter strings in the 'alignment' key.
            // Make sure we take only the first letter, to deal with strange entries like "NX" and "NY"

            $lawChaos = AlignmentLawChaos::tryFromString($value[0]) ??
                throw new InvalidArgumentException("Could not parse alignment.");
            $goodEvil = AlignmentGoodEvil::tryFromString($value[1]) ??
                throw new InvalidArgumentException("Could not parse alignment.");
            $item->alignment = new Alignment($lawChaos, $goodEvil);
        }

        $item->save();
        return $item;
    }

    public static function fromText(string $value, ?CreatureTypeEdition $parent = null): static
    {
        $item = new static();
        $item->creatureTypeEdition()->associate($parent);

        $item->alignment = Alignment::fromString($value);

        $item->save();
        return $item;
    }
}
