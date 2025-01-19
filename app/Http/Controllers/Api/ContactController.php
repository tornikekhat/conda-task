<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreRequest;
use App\Services\Interfaces\ContactServiceInterface;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(protected ContactServiceInterface $contactService)
    {
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->contactService->storeContact($request->validated());

        return response()->json($result['message'], $result['status']);
    }
}
