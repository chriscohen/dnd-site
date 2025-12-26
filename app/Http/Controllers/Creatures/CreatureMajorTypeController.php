<?php

declare(strict_types=1);

namespace App\Http\Controllers\Creatures;

use App\DTOs\Creatures\CreatureMajorTypeFullDTO;
use App\Http\Controllers\AbstractController;
use App\Models\Creatures\CreatureMajorType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatureMajorTypeController extends AbstractController
{
    protected string $entityType = CreatureMajorType::class;
    protected string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        /** @var CreatureMajorType|null $item */
        $item = $this->query
            ->where('slug', $slug)
            ->with([
                'editions'
            ])
            ->first();
        $item = CreatureMajorTypeFullDTO::fromModel($item);

        return response()->json($item);
    }

    public function list(Request $request): JsonResponse
    {
        $items = $this->query
            ->orderBy($this->orderKey)
            ->paginate()
            ->through(fn(CreatureMajorType $item) => CreatureMajorTypeFullDTO::fromModel($item));

        return response()->json($items);
    }
}
