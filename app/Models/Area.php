<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AreaType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?int $height
 * @property ?int $length
 * @property ?int $per_level
 * @property int $quantity
 * @property ?int $radius
 * @property ?int $width
 * @property AreaType $type
 */
class Area extends AbstractModel
{
    use HasUuids;

    public $timestamps = false;

    public $casts = [
        'type' => AreaType::class,
    ];

    public function toArrayShort(): array
    {
        return [
            'string' => $this->toString(),
        ];
    }

    public function toArrayLong(): array
    {
        return [
            'id' => $this->id,
            'height' => $this->height,
            'length' => $this->length,
            'per_level' => $this->per_level,
            'quantity' => $this->quantity,
            'radius' => $this->radius,
            'width' => $this->width,
            'type' => $this->type->toString(),
        ];
    }

    public function toString(): string
    {
        $output = ucfirst($this->type->toString());

        switch ($this->type) {
            default:
            case AreaType::LINE:
                return sprintf(
                    'Line (%s ft wide, %s ft long)',
                    $this->width,
                    $this->length
                );

            case AreaType::CUBE:
                $base = sprintf(
                    '%s %s ft cube',
                    $this->quantity,
                    $this->length
                );
                return empty($this->per_level) ? $base : $base . '/level';

            case AreaType::CONE:
                return sprintf(
                    'Cone (%s ft radius, %s ft long)',
                    $this->radius,
                    $this->length
                );

            case AreaType::SPHERE:
                return sprintf(
                    'Sphere (%s ft radius)',
                    $this->radius
                );

            case AreaType::CYLINDER:
                return sprintf(
                    'Cylinder (%s ft radius, %s ft high)',
                    $this->radius,
                    $this->height
                );
        }
    }
}
