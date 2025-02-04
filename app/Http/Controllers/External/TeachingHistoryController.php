<?php

namespace App\Http\Controllers\External;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Services\External\TeachingHistoryService;
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
     * @param string $lecturerId
     * @return JsonResponse
     */
    public function getTeachingHistory(string $lecturerId): JsonResponse
    {
        try {
            $data = $this->teachingHistoryService->getTeachingHistory($lecturerId);

            $message = !empty($data)
                ? 'Teaching history fetched successfully.'
                : 'Teaching history is not available at this time.';

            return ApiResponseClass::sendResponse(200, $message, $data);
        } catch (\Exception $e) {
            return ApiResponseClass::sendError(500, $e->getMessage());
        }
    }
}
