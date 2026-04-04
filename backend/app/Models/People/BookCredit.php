<?php

declare(strict_types=1);

namespace App\Models\People;

use App\Models\AbstractModel;
use App\Models\ModelInterface;
use App\Models\Sources\SourceEdition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property SourceEdition $edition
 * @property Person $person
 * @property string $role
 */
class BookCredit extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;
    public $table = 'credits';

    public function edition(): BelongsTo
    {
        return $this->belongsTo(SourceEdition::class, 'source_edition_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * @param array{
     *     person: string, (a slug)
     *     role: string,
     * } $value
     * @param SourceEdition $parent
     */
    public static function fromInternalJson(int|array|string $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->edition()->associate($parent);

        try {
            $person = Person::query()->where('slug', $value['person'])->firstOrFail();
        } catch (ModelNotFoundException) {
            die("Person not found: {$value['person']}\n");
        }
        $item->person()->associate($person);
        $item->role = $value['role'];

        $item->save();
        return $item;
    }

    public function toArrayFull(): array
    {
        return [
            'person' => $this->person->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'person' => $this->person->toArray($this->renderMode),
            'role' => $this->role
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
