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
     * @param string $lecturerId
     * @return array
     * @throws \Exception
     */
    public function fetchTeachingHistory(string $lecturerId): array
    {
        $cacheKey = "teaching_history_{$lecturerId}";
        return Cache::remember($cacheKey, now()->addMinutes(40320), function () use ($lecturerId) {
            $url = "https://api-pddikti.ridwaanhall.me/dosen/teaching-history/{$lecturerId}/";
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

                return [];
            } catch (GuzzleException $e) {
                return [];
            }
        });
    }
}
