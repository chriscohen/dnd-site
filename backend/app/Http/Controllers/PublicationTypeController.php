<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PublicationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class PublicationTypeController extends Controller
{
    public string $entityType = PublicationType::class;

    public function list(): JsonResponse
    {
        $json = [
            [
                'id' => PublicationType::OFFICIAL->value,
                'name' => PublicationType::OFFICIAL->toString(),
            ],
            [
                'id' => PublicationType::THIRD_PARTY->value,
                'name' => PublicationType::THIRD_PARTY->toString(),
            ],
            [
                'id' => PublicationType::HOMEBREW->value,
                'name' => PublicationType::HOMEBREW->toString(),
            ],
        ];
        return response()->json($json);
    }
}
