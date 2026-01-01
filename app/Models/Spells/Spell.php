<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Enums\GameEdition;
use App\Exceptions\DuplicateRecordException;
use App\Models\AbstractModel;
use App\Models\Media\Media;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 *
 * @property Collection<SpellEdition> $editions
 * @property ?Media $image
 * @property string $name
 * @property string $slug
 */
class Spell extends AbstractModel
{
    use HasUuids;
    use Searchable;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'range_is_touch' => 'boolean',
        'range_is_self' => 'boolean',
    ];

    public function editions(): HasMany
    {
        return $this->hasMany(SpellEdition::class, 'spell_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }


    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();

        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->name = $value['name'];
        $item->slug = $value['slug'] ?? static::makeSlug($value['name']);

        if (!empty($value['image'])) {
            $image = Media::fromInternalJson([
                'filename' => '/spells/' . $value['image'],
            ], $item);
            $item->image()->associate($image);
        }

        foreach ($value['editions'] ?? [] as $editionData) {
            $edition = SpellEdition::fromInternalJson($editionData, $item);
            $item->editions()->save($edition);
        }

        $item->save();
        return $item;
    }

    /**
     * @throws DuplicateRecordException
     */
    public static function from5eJson(array|string|int $value, ?ModelInterface $parent = null): static
    {
        $gameEdition = !empty($value['srd52']) ? GameEdition::FIFTH_REVISED : GameEdition::FIFTH;

        $item = Spell::query()->where('name', $value['name'])->first() ?? new static();
        $item->name = $value['name'];
        $item->slug = static::makeSlug($value['name']);
        $item->save();

        $existingEdition = $item->editions->firstWhere('game_edition', $gameEdition);

        if (empty($existingEdition)) {
            $edition = SpellEdition::from5eJson($value, $item);
            $item->editions()->save($edition);
        } else {
            throw new DuplicateRecordException(
                "Spell edition for Fifth Edition already exists for spell '{$item->name}'\n"
            );
        }

        $item->save();
        return $item;
    }
}
