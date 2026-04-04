<?php

namespace App\Models\Items;

use App\CommonMark\InternalLinkGenerator;
use App\Enums\GameEdition;
use App\Enums\Rarity;
use App\Models\AbstractModel;
use App\Models\ModelCollection;
use App\Models\ModelInterface;
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
 * @property GameEdition $game_edition
 * @property bool $is_default
 * @property bool $is_unique
 * @property ItemType $itemType
 * @property Uuid $item_type_id
 * @property int $price
 * @property int $quantity
 * @property Rarity $rarity
 * @property Collection<Reference> $references
 * @property Collection<SpellEdition> $spells
 * @property float $weight
 */
class ItemTypeEdition extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
        'is_primary' => 'boolean',
        'is_unique' => 'boolean',
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

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'entity');
    }

    public function spells(): BelongsToMany
    {
        return $this->belongsToMany(SpellEdition::class, 'spell_material_components');
    }

    public function toArrayFull(): array
    {
        return [
            'description' => $this->description(),
            'gameEdition' => $this->game_edition->toStringShort(),
            'isUnique' => $this->is_unique,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'rarity' => $this->rarity->toString(),
            'references' => ModelCollection::make($this->references)->toArray($this->renderMode),
            'weight' => $this->weight,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'is_default' => $this->is_default,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'];
        $item->itemType()->associate($parent);

        $item->is_default = $value['isDefault'] ?? false;

        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);
        $item->description = $value['description'];
        $item->is_unique = $value['isUnique'] ?? false;
        $item->price = !empty($value['price']) ?
            $item->priceFromString($value['price']) :
            null;
        $item->rarity = Rarity::tryFromString($value['rarity']);
        $item->quantity = $value['quantity'] ?? 1;
        $item->weight = $value['weight'] ?? null;

        foreach ($value['references'] ?? [] as $reference) {
            Reference::fromInternalJson($reference, $item);
        }

        $item->save();
        return $item;
    }
}
