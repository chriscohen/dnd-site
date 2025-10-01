<?php

declare(strict_types=1);

namespace App\Models\Skills;

use App\Models\AbstractModel;
use App\Models\ModelCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 *
 * @property Collection<SkillEdition> $editions
 * @property string $name
 */
class Skill extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function editions(): HasMany
    {
        return $this->hasMany(SkillEdition::class, 'skill_id');
    }

    public function toArrayFull(): array
    {
        return [
            'editions' => ModelCollection::make($this->editions)->toArray($this->renderMode),
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
