<?php

declare(strict_types=1);

namespace App\Models\Skills;

use App\Enums\Ability;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property ?string $alternate_name
 * @property GameEdition $game_edition
 * @property ?Ability $related_attribute
 * @property Skill $skill
 */
class SkillEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'related_attribute' => Ability::class,
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
            'alternateName' => $this->alternate_name,
            'gameEdition' => $this->game_edition->toStringShort(),
            'relatedAttribute' => $this->related_attribute->toStringShort()
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->skill()->associate($parent);
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->alternate_name = $value['alternateName'] ?? null;
        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);

        if (!empty($value['relatedAttribute'])) {
            $item->related_attribute = Ability::tryFromString($value['relatedAttribute']);
        }

        $item->save();
        return $item;
    }
}
