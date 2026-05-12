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
            PublicationType::OFFICIAL->value => PublicationType::OFFICIAL->toString(),
            PublicationType::THIRD_PARTY->value => PublicationType::THIRD_PARTY->toString(),
            PublicationType::HOMEBREW->value => PublicationType::HOMEBREW->toString(),
        ];
        return response()->json($json);
    }
}
