<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return $this->handleApiException($request, $e);
            }
        });
    }

    /**
     * Handle API exceptions
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException($request, Throwable $exception)
    {
        $statusCode = 500;

        if ($exception instanceof ValidationException) {
            $statusCode = 422;
            $errors = $exception->validator->errors()->toArray();

            return response()->json([
                'message' => 'Validation Error',
                'errors' => $errors
            ], $statusCode);
        }

        if ($exception instanceof AuthenticationException) {
            $statusCode = 401;
        } elseif ($exception instanceof AuthorizationException) {
            $statusCode = 403;
        } elseif ($exception instanceof ModelNotFoundException) {
            $statusCode = 404;
            return response()->json([
                'message' => 'Resource not found'
            ], $statusCode);
        }

        return response()->json([
            'message' => $exception->getMessage()
        ], $statusCode);
    }
}
