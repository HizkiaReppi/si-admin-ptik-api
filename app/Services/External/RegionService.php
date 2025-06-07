<?php

namespace App\Services\External;

use App\Interfaces\RegionRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class RegionService
{
    public function __construct(
        private readonly RegionRepositoryInterface $regionRepository
    ) {}

    /**
     * Mengambil data provinsi dengan caching.
     */
    public function getProvinces(): array
    {
        return Cache::remember('wilayah:provinces', now()->addDay(), function () {
            return $this->regionRepository->getProvinces();
        });
    }

    /**
     * Mengambil data kabupaten dengan caching.
     */
    public function getRegencies(string $provinceCode): array
    {
        $cacheKey = "wilayah:regencies:{$provinceCode}";
        return Cache::remember($cacheKey, now()->addDay(), function () use ($provinceCode) {
            return $this->regionRepository->getRegencies($provinceCode);
        });
    }

    /**
     * Mengambil data kecamatan dengan caching.
     */
    public function getDistricts(string $regencyCode): array
    {
        $cacheKey = "wilayah:districts:{$regencyCode}";
        return Cache::remember($cacheKey, now()->addDay(), function () use ($regencyCode) {
            return $this->regionRepository->getDistricts($regencyCode);
        });
    }

    /**
     * Mengambil data desa dengan caching.
     */
    public function getVillages(string $districtCode): array
    {
        $cacheKey = "wilayah:villages:{$districtCode}";
        return Cache::remember($cacheKey, now()->addDay(), function () use ($districtCode) {
            return $this->regionRepository->getVillages($districtCode);
        });
    }
}