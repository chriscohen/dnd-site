<?php

declare(strict_types=1);

namespace App\Http\Controllers\Creatures;

use App\DTOs\Creatures\CreatureFullDTO;
use App\DTOs\Creatures\CreatureSummaryDTO;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\CreatureListRequest;
use App\Models\Creatures\Creature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatureController extends AbstractController
{
    protected string $entityType = Creature::class;
    protected string $orderKey = 'name';

    public function get(Request $request, string $slug): JsonResponse
    {
        $this->preValidate($request);

        /** @var Creature|null $item */
        $item = $this->query
            ->where('slug', $slug)
            ->with([
                'editions'
            ])
            ->first();
        $item = CreatureFullDTO::fromModel($item);

        return response()->json($item ?? []);
    }

    public function list(CreatureListRequest $request): JsonResponse
    {
        $safeData = $request->validated();

        if (!$request->boolean('children')) {
            $this->query->whereNull('parent_id');
        }

        $this->query->orderBy($this->orderKey);

        $items = $this->query->paginate(20)->through(fn(Creature $item) => CreatureSummaryDTO::fromModel($item));

        return response()->json($items);
    }
}
