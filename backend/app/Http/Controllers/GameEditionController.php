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
            [
                GameEdition::ZERO->value => GameEdition::ZERO->toStringShort(),
                GameEdition::FIRST->value => GameEdition::FIRST->toStringShort(),
                GameEdition::SECOND->value => GameEdition::SECOND->toStringShort(),
                GameEdition::THIRD->value => GameEdition::THIRD->toStringShort(),
                GameEdition::TPF->value => GameEdition::TPF->toStringShort(),
                GameEdition::FOURTH->value => GameEdition::FOURTH->toStringShort(),
                GameEdition::FIFTH->value => GameEdition::FIFTH->toStringShort(),
                GameEdition::FIFTH_REVISED->value => GameEdition::FIFTH_REVISED->toStringShort(),
            ]
        ]);
    }
}
