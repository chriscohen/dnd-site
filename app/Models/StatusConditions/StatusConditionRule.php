<?php

declare(strict_types=1);

namespace App\Models\StatusConditions;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelMarkdown\MarkdownRenderer;

/**
 * @property Uuid $id
 *
 * @property string $rule
 * @property StatusConditionEdition $statusConditionEdition
 */
class StatusConditionRule extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    protected function rule(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => app(MarkdownRenderer::class)->toHtml($value),
        );
    }

    public function statusConditionEdition(): BelongsTo
    {
        return $this->belongsTo(StatusConditionEdition::class);
    }

    public function toArrayFull(): array
    {
        return [
            'statusConditionEdition' => $this->statusConditionEdition->toArray($this->renderMode),
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
            'rule' => $this->rule,
        ];
    }

    public function toString(): string
    {
        return $this->rule;
    }

    public static function fromInternalJson(array $value): static
    {
        throw new \Exception('Not implemented');
    }
}
