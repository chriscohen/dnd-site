<?php

declare(strict_types=1);

namespace App\Http\Controllers\Creatures;

use App\DTOs\Creatures\CreatureMainTypeFullDTO;
use App\Http\Controllers\AbstractController;
use App\Models\Creatures\CreatureMainType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatureMainTypeController extends AbstractController
{
    protected string $entityType = CreatureMainType::class;
    protected string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        /** @var CreatureMainType|null $item */
        $item = $this->query
            ->where('slug', $slug)
            ->with([
                'editions'
            ])
            ->first();
        $item = CreatureMainTypeFullDTO::fromModel($item);

        return response()->json($item);
    }

    public function list(Request $request): JsonResponse
    {
        $items = $this->query
            ->orderBy($this->orderKey)
            ->paginate()
            ->through(fn(CreatureMainType $item) => CreatureMainTypeFullDTO::fromModel($item));

        return response()->json($items);
    }
}
