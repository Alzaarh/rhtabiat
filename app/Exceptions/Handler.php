<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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

        $this->renderable(function (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json(['message' => 'Not found'], 404);
        });

        $this->renderable(function (HttpException $e) {
            switch ($e->getStatusCode()) {
                case 401:
                    return jsonResponse(['message' => 'Login first'], 401);
                case 403:
                    return jsonResponse(['message' => 'Forbidden'], 403);
            }
        });
    }
}
