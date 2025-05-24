<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    /**
     * Return a success JSON response
     */
    protected function success($data = null, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response
     */
    protected function error(string $message = null, int $code = 400, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Return a validation error JSON response
     */
    protected function validationError($errors, string $message = 'Validation failed')
    {
        return $this->error($message, 422, $errors);
    }
}