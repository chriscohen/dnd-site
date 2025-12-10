<?php

namespace App\Models\Items;

use App\CommonMark\InternalLinkGenerator;
use App\Enums\GameEdition;
use App\Enums\Rarity;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\Reference;
use App\Models\Spells\SpellEdition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use League\CommonMark\Extension\Mention\MentionExtension;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 *
 * @property string $description
 * @property GameEdition $gameEdition
 * @property bool $isDefault
 * @property bool $isUnique
 * @property Item $item
 * @property Uuid $itemId
 * @property int $price
 * @property int $quantity
 * @property Rarity $rarity
 * @property Collection<Reference> $references
 * @property Collection<SpellEdition> $spells
 * @property float $weight
 */
class ItemEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'gameEdition' => GameEdition::class,
        'isPrimary' => 'boolean',
        'isUnique' => 'boolean',
        'rarity' => Rarity::class,
    ];

    public function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => app(MarkdownRenderer::class)->addExtension(new MentionExtension())
                ->commonmarkOptions([
                    'mentions' => [
                        'internal_link' => [
                            'prefix' => '@',
                            'pattern' => '([a-zA-Z0-9]+):([a-z-]+):([\w\'& ]+)',
                            'generator' => new InternalLinkGenerator(),
                        ]
                    ]
                ])
                ->toHtml($value),
        );
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'itemId');
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function spells(): BelongsToMany
    {
        return $this->belongsToMany(SpellEdition::class, 'spellMaterialComponents');
    }

    public function toArrayFull(): array
    {
        return [
            'description' => $this->description(),
            'gameEdition' => $this->gameEdition->toStringShort(),
            'isUnique' => $this->isUnique,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'rarity' => $this->rarity->toString(),
            'references' => ModelCollection::make($this->references)->toArray($this->renderMode,),
            'weight' => $this->weight,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'isDefault' => $this->isDefault,
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
