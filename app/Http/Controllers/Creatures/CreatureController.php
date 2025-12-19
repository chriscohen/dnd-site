<?php

declare(strict_types=1);

namespace App\Http\Controllers\Creatures;

use App\Http\Controllers\AbstractController;
use App\Http\Requests\CreatureListRequest;
use App\Models\Creatures\Creature;
use Illuminate\Http\JsonResponse;

class CreatureController extends AbstractController
{
    protected string $entityType = Creature::class;
    protected string $orderKey = 'name';

    public function list(CreatureListRequest $request): JsonResponse
    {
        $safeData = $request->validated();

        if (!$request->boolean('children')) {
            $this->query->whereNull('parent_id');
        }

        $this->query->orderBy($this->orderKey);

        $items = $this->query->get();

        foreach ($items as $item) {
            $output[] = $item->toArray($this->getMode($request));
        }

        return response()->json($output ?? []);
    }
}
