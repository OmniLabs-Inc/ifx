<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

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
         # NOT FOUND HANDLER
         $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status_code' => '0',
                'status_text' => 'failed',
                'message' => 'NOT FOUND'
            ], 404);
        });

        # METHOD NOT FOUND HANDLER
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'status_code' => '0',
                'status_text' => 'failed',
                'message' => 'METHOD NOT ALLOWED'
            ], 405);
        });

         # TOO MANY EXCEPTION HANDLER
         $this->renderable(function (TooManyRequestsHttpException $e, $request) {
            return response()->json([
                'status_code' => '0',
                'status_text' => 'failed',
                'message' => 'IP BLOCKED'
            ], 429);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    # UNAUTHORIZED HANDLER
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage(), 'status_code' => '0', 'status_text' => 'failed'], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
