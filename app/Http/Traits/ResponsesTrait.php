<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait ResponsesTrait
{
    public function successResponse($message, $data = [], $statusCode = 200): JsonResponse
    {
        $data = array_merge($data, [
            'success' => true,
            'message' => $message
        ]);

        if (!isset($data['silent'])) {
            $data['silent'] = false;
        }

        return Response::json($data, $statusCode);
    }

    public function silentSuccessResponse($message, $data = [], $statusCode = 200): JsonResponse
    {
        $data['silent'] = true;

        return $this->successResponse($message, $data, $statusCode);
    }

    public function failResponse($message, $data = [], $statusCode = 400): JsonResponse
    {
        $data = array_merge($data, [
            'success' => false,
            'message' => $message
        ]);

        return Response::json($data, $statusCode);
    }
}
