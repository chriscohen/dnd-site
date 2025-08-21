<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GameEdition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property Collection<CharacterClass> $classes
 * @property GameEdition $game_edition
 * @property string $name
 * @property string $description
 * @property string $higher_level
 * @property bool $range_is_self
 * @property bool $range_is_touch
 * @property int $range_number
 * @property Distance $range_unit
 * @property MagicSchool $school
 * @property string $slug
 */
class Spell extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'range_is_touch' => 'boolean',
        'range_is_self' => 'boolean',
    ];

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(SpellComponentType::class);
    }

    public function rangeUnit(): BelongsTo
    {
        return $this->belongsTo(Distance::class, 'id', 'range_unit');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(MagicSchool::class, 'id');
    }

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'source');
    }
}
