<?php

declare(strict_types=1);

namespace App\Http\Controllers\Creatures;

use App\DTOs\Creatures\CreatureTypeFullDTO;
use App\DTOs\Creatures\CreatureSummaryDTO;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\CreatureListRequest;
use App\Models\Creatures\CreatureType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatureTypeController extends AbstractController
{
    protected string $entityType = CreatureType::class;
    protected string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        /** @var CreatureType|null $item */
        $item = $this->query
            ->where('slug', $slug)
            ->with([
                'editions',
                'editions.abilities',
                'editions.alignment',
                'editions.armorClass',
                'editions.armorClass.items',
                'editions.hitPoints',
                'editions.media',
                'editions.movementSpeeds',
                'editions.type',
                'editions.type.mainType',
                'editions.type.origin'
            ])
            ->first();
        $item = CreatureTypeFullDTO::fromModel($item);

        return response()->json($item);
    }

    public function list(CreatureListRequest $request): JsonResponse
    {
        $safeData = $request->validated();

        if (!$request->boolean('children')) {
            $this->query->whereNull('parent_id');
        }

        $this->query->orderBy($this->orderKey);

        $items = $this->query->paginate(50)->through(fn(CreatureType $item) => CreatureSummaryDTO::fromModel($item));

        return response()->json($items);
    }
}
