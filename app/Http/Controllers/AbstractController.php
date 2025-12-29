<?php

namespace App\Http\Controllers;

use App\DTOs\CharacterClasses\CharacterClassSummaryDTO;
use App\Enums\GameEdition;
use App\Enums\JsonRenderMode;
use App\Models\AbstractModel;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\ModelInterface;
use App\Rules\ValidGameEdition;
use App\Rules\ValidMode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class AbstractController extends Controller implements ControllerInterface
{
    protected string $entityType = AbstractModel::class;
    protected string $order = 'ASC';
    protected string $orderKey = 'name';
    protected Builder $query;
    protected string $whereField = 'slug';
    protected bool $hasEditions = true;

    public function __construct()
    {
        $this->query = $this->getQuery();
    }

    public function editionQuery(string $editions): self
    {
        if ($this->hasEditions) {
            $parameters = $this->getEditionsFromQueryString($editions);

            $this->query->whereHas('editions', function ($query) use ($parameters) {
                $query->whereIn('game_edition', $parameters);
            });
        }

        return $this;
    }

    /**
     * Turn editions from querystring format into GameEdition enums.
     *
     * @param  string  $queryString
     *   For example, "2e,3e,5.2014"
     * @return GameEdition[]
     *   The
     */
    public function getEditionsFromQueryString(string $queryString): array
    {
        $editions = explode(',', $queryString);

        // If all editions, don't add anything to the query.
        if (in_array('all', $editions)) {
            return [];
        }

        $parameters = [];

        foreach ($editions as $edition) {
            $enum = GameEdition::tryFromString($edition);

            if (!empty($enum)) {
                $parameters[] = $enum;
            }
        }

        // Fiddle the results so 3rd edition includes 3.5 and vice versa.
        if (in_array(GameEdition::THIRD, $parameters)) {
            $parameters[] = GameEdition::TPF;
        } elseif (in_array(GameEdition::TPF, $parameters)) {
            $parameters[] = GameEdition::THIRD;
        }

        return $parameters;
    }

    public function get(Request $request, string $slug): JsonResponse
    {
        if (!empty($request->get('editions'))) {
            $this->editionQuery($request->get('editions'));
        }

        /** @var ModelInterface $model */
        $model = $this->query->where($this->whereField, $slug)->first();

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
        $items = $this->query
            ->orderBy($this->orderKey)
            ->paginate(50)
            ->through(fn (CharacterClass $item) => CharacterClassSummaryDTO::fromModel($item));

        return response()->json($items->withQueryString());
    }
}
