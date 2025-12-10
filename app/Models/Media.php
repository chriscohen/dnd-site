<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property ?string $collectionName
 * @property string $disk
 * @property string $filename
 * @property ?string $mimeType
 * @property ?string $name
 * @property ?int $size
 */
class Media extends AbstractModel
{
    use HasUuids;

    /**
     * @param  array{
     *     filename: string,
     *     disk: string,
     *     name?: string,
     *     collectionName?: string,
     *     mimeType?: string
     * } $data
     * @return self
     */
    public static function createFromExisting(array $data): self
    {
        $disk = Storage::disk($data['disk']);

        // Ensure file exists.
        if (!$disk->exists($data['filename'])) {
            throw new FileNotFoundException(
                'Could not find file: ' . $data['filename'] . ' on disk ' . $data['disk']
            );
        }

        $data['size'] = $disk->size($data['filename']);
        $data['mimeType'] = $disk->mimeType($data['filename']);

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
            'mimeType' => $this->mime_type,
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

    public static function fromInternalJson(array $value): static
    {
        $item = new static();
        $item->id = $value['id'] ?? Uuid::uuid4();
        $item->collectionName = $value['collectionName'] ?? null;
        $item->disk = $value['disk'];
        $item->filename = $value['filename'];
        $item->mimeType = $value['mimeType'] ?? null;
        $item->name = $value['name'] ?? null;
        $item->size = $value['size'] ?? null;
        return $item;
    }
}
