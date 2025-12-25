<?php

declare(strict_types=1);

namespace App\Models\Conditions;

use App\Enums\GameEdition;
use App\Models\AbstractModel;
use App\Models\ModelInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 *
 * @property ?string $description
 * @property GameEdition $game_edition
 * @property Collection<StatusConditionRule> $rules
 * @property Condition $statusCondition
 * @property Uuid $statusConditionId
 */
class ConditionEdition extends AbstractModel
{
    use HasUuids;

    protected $fillable = [
        'game_edition',
    ];

    public $timestamps = false;

    public $casts = [
        'game_edition' => GameEdition::class,
    ];

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => app(MarkdownRenderer::class)->toHtml($value),
        );
    }

    protected function gameEdition(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => GameEdition::tryFrom($value),
        );
    }

    public function rules(): HasMany
    {
        return $this->hasMany(StatusConditionRule::class);
    }

    public function toArrayFull(): array
    {
        return [
            'status_condition' => $this->statusCondition->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [
            'description' => $this->description,
        ];
    }

    public function toString(): string
    {
        return $this->description;
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();

        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->description = $value['description'] ?? null;
        $item->game_edition = GameEdition::tryFromString($value['gameEdition']);
        $item->condition()->associate($parent);

        foreach ($value['rules'] ?? [] as $rule) {
            StatusConditionRule::fromInternalJson($rule, $item);
        }

        $item->save();
        return $item;
    }
}
