<?php

namespace App\Common\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponsible
{
    protected function success($data = [], int $status = 200): JsonResponse
    {
        return new JsonResponse([
            'data' => $data,
        ], $status);
    }

    protected function fail($data = [], string $message = '', int $status = 400): JsonResponse
    {
        return new JsonResponse([
            'data'    => $data,
            'message' => $message,
        ], $status);
    }
}
