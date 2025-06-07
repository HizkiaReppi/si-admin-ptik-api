<?php

namespace App\Repositories\External;

use App\Interfaces\RegionRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class RegionRepository implements RegionRepositoryInterface
{
    private const BASE_URL = 'https://wilayah.id/api';

    /**
     * Fungsi private untuk melakukan request ke API wilayah.id.
     *
     * @param string $endpoint
     * @return array
     * @throws RequestException
     */
    private function makeRequest(string $endpoint): array
    {
        $response = Http::get(self::BASE_URL . '/' . $endpoint);

        $response->throw();

        return $response->json('data', []);
    }

    public function getProvinces(): array
    {
        return $this->makeRequest('provinces.json');
    }

    public function getRegencies(string $provinceCode): array
    {
        return $this->makeRequest("regencies/{$provinceCode}.json");
    }

    public function getDistricts(string $regencyCode): array
    {
        return $this->makeRequest("districts/{$regencyCode}.json");
    }

    public function getVillages(string $districtCode): array
    {
        return $this->makeRequest("villages/{$districtCode}.json");
    }
}