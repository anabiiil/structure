<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Success response
     *
     * @param mixed|null $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public static function success(mixed $data = null, string $message = 'Success', int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param mixed|null $errors
     * @param int $status
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', mixed $errors = null, int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $status);
    }

    /**
     * Validation error response
     *
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function validationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return self::error($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_FORBIDDEN);
    }

    /**
     * Server error response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return self::error($message, null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Created response
     *
     * @param mixed|null $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created(mixed $data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Updated response
     *
     * @param mixed|null $data
     * @param string $message
     * @return JsonResponse
     */
    public static function updated(mixed $data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_OK);
    }

    /**
     * Deleted response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return self::success(null, $message, Response::HTTP_OK);
    }

    /**
     * Paginated response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function paginated(mixed $data, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_OK);
    }

    /**
     * No content response
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Custom response
     *
     * @param bool $success
     * @param string $message
     * @param mixed|null $data
     * @param int $status
     * @param array $additional
     * @return JsonResponse
     */
    public static function custom(bool $success, string $message, mixed $data = null, int $status = Response::HTTP_OK, array $additional = []): JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        // Merge any additional fields
        $response = array_merge($response, $additional);

        return response()->json($response, $status);
    }
}
