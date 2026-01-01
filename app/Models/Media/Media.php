<?php

declare(strict_types=1);

namespace App\Models\Media;

use App\Enums\MediaType;
use App\Models\AbstractModel;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\ModelInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?string $collection_name
 * @property string $disk
 * @property string $filename
 * @property MediaType $media_type
 * @property ?string $mime_type
 * @property ?string $name
 * @property ?int $size
 *
 * @property Collection<CreatureTypeEdition> $creatureTypeEditions
 */
class Media extends AbstractModel
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'media_type' => MediaType::class
        ];
    }

    public function creatureTypeEditions(): MorphToMany
    {
        return $this->morphedByMany(CreatureTypeEdition::class, 'entity', 'media_entity');
    }

    /**
     * @param  array{
     *     filename: string,
     *     disk?: string,
     *     name?: string,
     *     collection_name?: string,
     *     mime_type?: string,
     *     media_type?: string
     * } $data
     * @return self
     */
    public static function createFromExisting(array $data): self
    {
        $diskName = $data['disk'] ?? 's3';
        $disk = Storage::disk($diskName);

        // Ensure file exists.
        if (!$disk->exists($data['filename'])) {
            throw new FileNotFoundException(
                'Could not find file: ' . $data['filename'] . ' on disk ' . $diskName
            );
        }

        $data['size'] = $disk->size($data['filename']);
        $data['mime_type'] = $disk->mimeType($data['filename']);

        if (empty($data['media_type'])) {
            $data['media_type'] = MediaType::IMAGE;
        }

        return Media::create($data);
    }

    public function getUrl(): string
    {
        return Storage::disk($this->disk)->url($this->filename);
    }

    public static function fromInternalJson(array|string|int $value, ModelInterface $parent = null): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->collection_name = $value['collectionName'] ?? null;
        $item->disk = $value['disk'] ?? 's3';
        $item->filename = $value['filename'];
        $item->media_type = !empty($value['mediaType']) ?
            MediaType::tryFromString($value['mediaType']) :
            MediaType::IMAGE;
        $item->mime_type = $value['mimeType'] ?? null;
        $item->name = $value['name'] ?? null;
        $item->size = $value['size'] ?? null;
        $item->save();
        return $item;
    }
}
