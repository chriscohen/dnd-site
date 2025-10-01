<?php

declare(strict_types=1);

namespace App\Models\Skills;

use App\Enums\Attribute;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property string $alternate_name
 * @property GameEdition $game_edition
 * @property ?Attribute $related_attribute
 * @property Skill $skill
 */
class SkillEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'related_attribute' => Attribute::class,
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'alternate_name' => $this->alternate_name,
            'game_edition' => $this->game_edition->toStringShort(),
            'related_attribute' => $this->related_attribute->toStringShort()
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
