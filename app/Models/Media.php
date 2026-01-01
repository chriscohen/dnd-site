<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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

    /**
     * @param  array{
     *     filename: string,
     *     disk?: string,
     *     name?: string,
     *     collection_name?: string,
     *     mime_type?: string
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

        return Media::create($data);
    }

    public function getUrl(): string
    {
        return Storage::disk($this->disk)->url($this->filename);
    }

    public function toArrayFull(): array
    {
        return [
            'filename' => $this->filename,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
        ];
    }

    public function toArrayShort(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->getUrl(),
        ];
    }

    public function toArrayTeaser(): array
    {
        return [];
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
