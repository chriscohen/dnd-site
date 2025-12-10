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
 * @property string $alternateName
 * @property GameEdition $gameEdition
 * @property ?Attribute $relatedAttribute
 * @property Skill $skill
 */
class SkillEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'gameEdition' => GameEdition::class,
        'relatedAttribute' => Attribute::class,
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skillId');
    }

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'alternateName' => $this->alternateName,
            'gameEdition' => $this->gameEdition->toStringShort(),
            'relatedAttribute' => $this->relatedAttribute->toStringShort()
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
