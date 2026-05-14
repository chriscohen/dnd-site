<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GameEdition;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class GameEditionController extends Controller
{
    public function list(): JsonResponse
    {
        return response()->json([
            'data' => [
                [
                    'id' => GameEdition::ZERO->value,
                    'shortName' => GameEdition::ZERO->toStringShort(),
                    'name' => GameEdition::ZERO->toString(),
                ],
                [
                    'id' => GameEdition::FIRST->value,
                    'shortName' => GameEdition::FIRST->toStringShort(),
                    'name' => GameEdition::FIRST->toString(),
                ],
                [
                    'id' => GameEdition::SECOND->value,
                    'shortName' => GameEdition::SECOND->toStringShort(),
                    'name' => GameEdition::SECOND->toString(),
                ],
                [
                    'id' => GameEdition::THIRD->value,
                    'shortName' => GameEdition::THIRD->toStringShort(),
                    'name' => GameEdition::THIRD->toString(),
                ],
                [
                    'id' => GameEdition::TPF->value,
                    'shortName' => GameEdition::TPF->toStringShort(),
                    'name' => GameEdition::TPF->toString(),
                ],
                [
                    'id' => GameEdition::FOURTH->value,
                    'shortName' => GameEdition::FOURTH->toStringShort(),
                    'name' => GameEdition::FOURTH->toString(),
                ],
                [
                    'id' => GameEdition::FIFTH->value,
                    'shortName' => GameEdition::FIFTH->toStringShort(),
                    'name' => GameEdition::FIFTH->toString(),
                ],
                [
                    'id' => GameEdition::FIFTH_REVISED->value,
                    'shortName' => GameEdition::FIFTH_REVISED->toStringShort(),
                    'name' => GameEdition::FIFTH_REVISED->toString(),
                ]
            ]
        ]);
    }
}
