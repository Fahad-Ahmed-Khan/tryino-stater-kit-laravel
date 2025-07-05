<?php

namespace App\Traits\API;

use Illuminate\Http\JsonResponse;

trait APIResponse
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => [],
        ], $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function error(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => $message,
            'errors' => []
        ], $statusCode);
    }

    /**
     * Return a validation error response.
     *
     * @param array $errors
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function validationError(array $errors, string $message = 'Validation Error', int $statusCode = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Return a not found response.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function notFound(string $message = 'Resource Not Found', int $statusCode = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => $message,
            'errors' => []
        ], $statusCode);
    }

}