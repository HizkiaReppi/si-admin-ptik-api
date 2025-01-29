<?php

namespace App\Helpers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApiResponseHelper
{
    /**
     * Generate pagination data.
     *
     * @param LengthAwarePaginator $dataWithPagination
     * @return array
     */
    public function generatePagination(LengthAwarePaginator $dataWithPagination): array
    {
        $links = [];
        array_push($links, [
            'url' => $dataWithPagination->previousPageUrl(),
            'label' => '&laquo; Previous',
            'active' => false,
        ]);

        for ($i = 1; $i <= $dataWithPagination->lastPage(); $i++) {
            array_push($links, [
                'url' => $dataWithPagination->url($i),
                'label' => "$i",
                'active' => $i === $dataWithPagination->currentPage(),
            ]);
        }
        array_push($links, [
            'url' => $dataWithPagination->nextPageUrl(),
            'label' => 'Next &raquo;',
            'active' => false,
        ]);

        return [
            'current_page' => $dataWithPagination->currentPage(),
            'first_page_url' => $dataWithPagination->url(1),
            'from' => $dataWithPagination->firstItem(),
            'last_page' => $dataWithPagination->lastPage(),
            'last_page_url' => $dataWithPagination->url($dataWithPagination->lastPage()),
            'links' => $links,
            'next_page_url' => $dataWithPagination->nextPageUrl(),
            'path' => $dataWithPagination->url(1),
            'per_page' => $dataWithPagination->perPage(),
            'prev_page_url' => $dataWithPagination->previousPageUrl(),
            'to' => $dataWithPagination->lastItem(),
            'total' => $dataWithPagination->total(),
        ];
    }
}