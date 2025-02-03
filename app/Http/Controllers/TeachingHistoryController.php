<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Services\TeachingHistoryService;
use Illuminate\Http\JsonResponse;

class TeachingHistoryController extends Controller
{
    private TeachingHistoryService $teachingHistoryService;

    public function __construct(TeachingHistoryService $teachingHistoryService)
    {
        $this->teachingHistoryService = $teachingHistoryService;
    }

    /**
     * Endpoint untuk mendapatkan teaching history dari API eksternal.
     *
     * @param string $dosenId
     * @return JsonResponse
     */
    public function getTeachingHistory(string $dosenId): JsonResponse
    {
        try {
            $data = $this->teachingHistoryService->getTeachingHistory($dosenId);
            return ApiResponseClass::sendResponse(200, 'Teaching history fetched successfully', $data);
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
