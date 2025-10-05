<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Log the exception
        Log::error('Application Error: ' . $exception->getMessage(), [
            'exception' => $exception,
            'request' => $request->all(),
            'url' => $request->url(),
            'method' => $request->method(),
        ]);

        // Handle specific exception types
        if ($exception instanceof \Illuminate\Database\QueryException) {
            return $this->handleDatabaseError($request, $exception);
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->handleAuthenticationError($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->handleValidationError($request, $exception);
        }

        // Handle "Illegal offset type" errors specifically
        if (strpos($exception->getMessage(), 'Illegal offset type') !== false) {
            return $this->handleIllegalOffsetError($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle database errors
     */
    protected function handleDatabaseError($request, $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Database Error',
                'message' => 'A database error occurred. Please try again.',
                'debug' => config('app.debug') ? $exception->getMessage() : null
            ], 500);
        }

        return response()->view('errors.database', [
            'exception' => $exception,
            'message' => 'Database Error',
            'description' => 'A database error occurred. Please try again.'
        ], 500);
    }

    /**
     * Handle authentication errors
     */
    protected function handleAuthenticationError($request, $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Authentication Required',
                'message' => 'Please login to access this resource.'
            ], 401);
        }

        return redirect()->route('login')->with('error', 'Please login to access this resource.');
    }

    /**
     * Handle validation errors
     */
    protected function handleValidationError($request, $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => 'The given data was invalid.',
                'errors' => $exception->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($exception->errors())
            ->withInput();
    }

    /**
     * Handle "Illegal offset type" errors specifically
     */
    protected function handleIllegalOffsetError($request, $exception)
    {
        Log::critical('Illegal Offset Type Error', [
            'exception' => $exception,
            'request' => $request->all(),
            'url' => $request->url(),
            'method' => $request->method(),
            'stack_trace' => $exception->getTraceAsString()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Application Error',
                'message' => 'An application error occurred. Please try again.',
                'debug' => config('app.debug') ? $exception->getMessage() : null
            ], 500);
        }

        return response()->view('errors.illegal-offset', [
            'exception' => $exception,
            'message' => 'Application Error',
            'description' => 'An application error occurred. Please try again.'
        ], 500);
    }
}