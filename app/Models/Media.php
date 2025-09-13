<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\JsonRenderMode;
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
 * @property ?string $mime_type
 * @property ?string $name
 * @property ?int $size
 */
class Media extends AbstractModel
{
    use HasUuids;

    public array $schema = [
        JsonRenderMode::SHORT->value => [
            'id' => 'uuid',
            'url' => 'getUrl()'
        ],
        JsonRenderMode::FULL->value => [

        ]
    ];

    /**
     * @param  array{
     *     filename: string,
     *     disk: string,
     *     name?: string,
     *     collection_name?: string,
     *     mime_type?: string
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
        $data['mime_type'] = $disk->mimeType($data['filename']);

        return Media::create($data);
    }

    public function getUrl(): string
    {
        return Storage::disk($this->disk)->url($this->filename);
    }
}
