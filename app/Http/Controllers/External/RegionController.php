<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Services\External\RegionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    public function __construct(
        private readonly RegionService $regionService
    ) {}

    private function handleServiceCall(callable $serviceCall, string $logContext): JsonResponse
    {
        try {
            $data = $serviceCall();
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch {$logContext}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Gagal mengambil data dari server wilayah.'
            ], 503);
        }
    }

    public function provinces(): JsonResponse
    {
        return $this->handleServiceCall(
            fn() => $this->regionService->getProvinces(),
            'provinces'
        );
    }

    public function regencies(string $provinceCode): JsonResponse
    {
        return $this->handleServiceCall(
            fn() => $this->regionService->getRegencies($provinceCode),
            "regencies for province {$provinceCode}"
        );
    }

    public function districts(string $regencyCode): JsonResponse
    {
        return $this->handleServiceCall(
            fn() => $this->regionService->getDistricts($regencyCode),
            "districts for regency {$regencyCode}"
        );
    }

    public function villages(string $districtCode): JsonResponse
    {
        return $this->handleServiceCall(
            fn() => $this->regionService->getVillages($districtCode),
            "villages for district {$districtCode}"
        );
    }

    public function clearCache(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|starts_with:wilayah:'
        ]);

        Cache::forget($validated['key']);

        return response()->json(['message' => 'Cache for ' . $validated['key'] . ' cleared.']);
    }
}