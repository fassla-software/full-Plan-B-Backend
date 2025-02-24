<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function errorResponse($message = 'Error', $status = 400)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => null
        ], $status);
    }

    protected function paginatedResponse($paginationData, $message = 'Success', $status = 200)
    {
        return response()->json(
            array_merge(
                [
                    'status' => $status,
                    'message' => $message
                ],
                $paginationData->toArray() // Convert pagination object to an array
            ),
            $status
        );
    }
}
