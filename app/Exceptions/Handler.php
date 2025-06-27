<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {  
        if ($e instanceof ValidationException) {
            return $this->invalidJson($request, $e);
        }
        return parent::render($request, $e);
    }
    /**
     * Format JSON validation error responses.
     */
    public function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return response()->json([
            'error' => [
                'code'    => 'VALIDATION_ERROR',
                'message' => $exception->getMessage(),
                'details' => $exception->errors(),
            ]
        ], $exception->status);
    }
}
