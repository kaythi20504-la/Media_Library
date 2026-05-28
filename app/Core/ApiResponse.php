<?php

namespace App\Core;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success'): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error(string $message = 'Error', $data = null): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ];
    }
}