<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CampaignSetting;
use App\Models\ModelCollection;
use App\Models\Sources\Source;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
    public static int $max_search_results = 10;
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q');
        $remainingCount = static::$max_search_results;
        $output = new Collection();

        // Define the models, and the order, in which we want to search things.
        $models = [
            Source::class,
            CampaignSetting::class,
        ];

        foreach ($models as $model) {
            // Limit search query based on how many results we have left.
            /** @var LengthAwarePaginator $results */
            $results = $model::search($query)->paginate($remainingCount);

            // Merge the results into our output.
            $output = $output->merge($results->items());

            // Subtract the actual number of results we got.
            $remainingCount -= $results->count();

            if ($remainingCount <= 0) {
                break;
            }
        }

        return response()->json(ModelCollection::make($output)->toSearchResult());
    }
}
