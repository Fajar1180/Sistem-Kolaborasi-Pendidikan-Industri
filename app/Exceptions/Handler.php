<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response|JsonResponse
    {
        // Handle validation exceptions
        if ($exception instanceof ValidationException) {
            return $this->handleValidationException($request, $exception);
        }

        // JSON response for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() ?: 'Server error',
                'error' => config('app.debug') ? $exception->getMessage() : 'Internal server error',
            ], $this->getStatusCode($exception));
        }

        // Default Laravel exception handling
        return parent::render($request, $exception);
    }

    /**
     * Handle validation exception responses.
     */
    private function handleValidationException($request, ValidationException $exception): Response|JsonResponse
    {
        $errors = $exception->validator->errors()->toArray();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors,
            ], 422);
        }

        // Redirect back with errors for form submissions
        return redirect()
            ->back()
            ->withErrors($errors)
            ->withInput();
    }

    /**
     * Get HTTP status code from exception.
     */
    private function getStatusCode(Throwable $exception): int
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return match (get_class($exception)) {
            'Illuminate\Auth\AuthenticationException' => 401,
            'Illuminate\Auth\Access\AuthorizationException' => 403,
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' => 404,
            'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException' => 405,
            default => 500,
        };
    }
}
