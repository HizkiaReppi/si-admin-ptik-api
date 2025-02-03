<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class TeachingHistoryRepository
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout'  => 60.0,
        ]);
    }

    /**
     * Fetch teaching history dari API eksternal.
     *
     * @param string $dosenId
     * @return array
     * @throws \Exception
     */


    public function fetchTeachingHistory(string $dosenId): array
    {
        $cacheKey = "teaching_history_{$dosenId}";
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dosenId) {
            $url = "https://api-pddikti.ridwaanhall.me/dosen/teaching-history/{$dosenId}/";
            try {
                $response = $this->client->request('GET', $url, [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $body = $response->getBody()->getContents();
                    $data = json_decode($body, true);
                    return $data ?: [];
                }
                throw new \Exception("Unexpected status code: " . $response->getStatusCode());
            } catch (GuzzleException $e) {
                throw new \Exception("Failed to fetch teaching history: " . $e->getMessage());
            }
        });
    }
}
