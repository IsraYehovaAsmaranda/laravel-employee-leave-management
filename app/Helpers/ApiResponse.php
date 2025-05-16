<?php

namespace App\Helpers;

class ApiResponse
{
    public static function send($data, $message = 'success', $code = 200, $withPagination = false)
    {
        if ($withPagination == false) {
            return response([
                'message' => $message,
                'data' => $data,
                'paging' => null
            ], $code);
        } else {
            return response([
                'message' => $message,
                'data' => $data->items(),
                'paging' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                    'next_page_url' => $data->nextPageUrl(),
                    'prev_page_url' => $data->previousPageUrl(),
                ]
            ], $code);
        }
    }
}
