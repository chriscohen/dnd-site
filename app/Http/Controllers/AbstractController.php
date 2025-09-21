<?php

namespace App\Http\Controllers;

use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class AbstractController implements ControllerInterface
{
    protected string $entityType = AbstractModel::class;
    protected string $order = 'ASC';
    protected string $orderKey = '';
    protected Builder $query;

    public function __construct()
    {
        $this->query = $this->getQuery();
    }

    public function get(Request $request, string $slug): JsonResponse
    {
        $model = $this->query->where('slug', $slug)->first();

        return response()->json($model->toArray($this->getMode($request)));
    }

    public function getMode(Request $request): JsonRenderMode
    {
        return JsonRenderMode::tryFromString($request->query('mode', 'short'));
    }

    public function getQuery(): Builder
    {
        $limit = config('api.MAX_ITEMS_PER_REQUEST', 100);
        return $this->entityType::query()
            ->orderBy($this->orderKey, $this->order)
            ->limit($limit);
    }

    public function index(Request $request): JsonResponse
    {
        $items = $this->query->get();
        $output = [];

        foreach ($items as $item) {
            $output[] = $item->toArray($this->getMode($request));
        }

        return response()->json($output);
    }

    public function editionQuery(string $editions): self
    {
        $editions = explode(',', $editions);

        // If all editions, don't add anything to the query.
        if (in_array('all', $editions)) {
            return $this;
        }

        $parameters = [];

        foreach ($editions as $edition) {
            $enum = GameEdition::tryFromString($edition);

            if (!empty($enum)) {
                $parameters[] = $enum;
            }
        }

        // Fiddle the results so 3rd edition includes 3.5 and vice versa.
        if (in_array(GameEdition::THIRD->value, $parameters)) {
            $parameters[] = GameEdition::TPF->value;
        } elseif (in_array(GameEdition::TPF->value, $parameters)) {
            $parameters[] = GameEdition::THIRD->value;
        }

        $this->query->whereIn('game_edition', $parameters);
        return $this;
    }
}
