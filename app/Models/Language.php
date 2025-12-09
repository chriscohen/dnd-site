<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 * @property string $slug
 * @property string $name
 *
 * @property bool $isExotic
 * @property string $scriptName
 *
 */
class Language extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public function toArrayFull(): array
    {
        return [];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'isExotic' => $this->isExotic,
            'scriptName' => $this->scriptName,
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
    }
}
