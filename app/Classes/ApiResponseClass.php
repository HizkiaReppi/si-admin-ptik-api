<?php

namespace App\Classes;

use Illuminate\Http\JsonResponse;

class ApiResponseClass
{
    /**
     * Send success response format
     * 
     * @param int $code
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public static function sendResponse(int $code = 200, string $message, array $data = []): JsonResponse
    {
        $response = [
            'success' => true,
            'code' => $code,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Send success response format with pagination
     * 
     * @param int $code
     * @param string $message
     * @param array $data
     * @param array $pagination
     * @return JsonResponse
     */
    public static function sendResponseWithPagination(int $code = 200, string $message, array $data = [], array $pagination): JsonResponse
    {
        $response = [
            'success' => true,
            'code' => $code,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
            $response['pagination'] = $pagination;
        }

        return response()->json($response, $code);
    }

    /**
     * Send error response format
     * 
     * @param int $code
     * @param string $error
     * @param array|object $data
     * @return JsonResponse
     */
    public static function sendError(int $code = 400, string $error, array|object $data = []): JsonResponse
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $error,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}