<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FormatterHelper
{
    /**
     * Convert camelCase to snake_case.
     *
     * @param array $data
     * @return array
     */
    public function camelToSnake(array $data): array
    {
        $snakeData = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $snakeData[$key] = $this->camelToSnake($value);
            } elseif (is_object($value)) {
                $snakeData[$key] = $this->camelToSnake((array) $value);
            } else {
                $snakeData[Str::snake($key)] = $value;
            }
        }

        return $snakeData;
    }
}