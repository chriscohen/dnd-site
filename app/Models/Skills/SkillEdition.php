<?php

declare(strict_types=1);

namespace App\Models\Skills;

use App\Enums\AbilityScoreType;
use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use App\Models\Text\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property ?string $alternate_name
 * @property GameEdition $game_edition
 * @property ?AbilityScoreType $related_ability
 * @property Skill $skill
 * @property Collection<TextEntry> $entries
 */
class SkillEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'related_ability' => AbilityScoreType::class,
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function entries(): MorphMany
    {
        return $this->morphMany(TextEntry::class, 'parent');
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
            'relatedAbility' => $this->related_ability->toStringShort()
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

        if (!empty($value['relatedAbility'])) {
            $item->related_ability = AbilityScoreType::tryFromString($value['relatedAbility']);
        }

        $i = 0;
        foreach ($value['entries'] ?? [] as $entry) {
            TextEntry::fromInternalJson($entry, $item, ++$i);
        }

        $item->save();
        return $item;
    }
}
