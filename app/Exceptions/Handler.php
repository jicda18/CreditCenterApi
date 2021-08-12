<?php

namespace App\Exceptions;

use App\Traits\ExceptionRenderResponse;
use BadMethodCallException;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ExceptionRenderResponse;

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
        $this->renderable(function (NotFoundHttpException $exception) {
            return $this->response('Endpoint not found.', 404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $exception) {
            return $this->response('Method not allowed in this Endpoint.');
        });

        $this->renderable(function (AuthenticationException $exception) {
            return $this->response('Unauthenticated.', 401);
        });

        $this->renderable(function (ValidationException $exception) {
            return $this->response($exception->validator->errors()->getMessages(), 422);
        });

        $this->renderable(function (ModelNotFoundException $exception) {
            $model = strtolower(class_basename($exception->getModel()));

            return $this->response($this->formatted('Don\'t exist any instance of %s with this identify.', [$model]));
        });

        $this->renderable(function (HttpException $exception) {
            $response = $this->response($exception->getMessage(), $exception->getStatusCode());
            $response->headers->add($exception->getHeaders());

            return $response;
        });

        $this->renderable(function (QueryException $exception) {
            $codeMysql = $exception->errorInfo[1];
            if ($codeMysql == 1451) {
                return $this->response('Can\'t delete permanently the resource.');
            } elseif ($codeMysql == 1062) {
                return $this->response('Can\'t duplicate entry.');
            }
            //Todo: manejar errores de base datos
        });

        $this->renderable(function (BadMethodCallException $exception) {
            return $this->response('Method not allowed on this endpoint.', 405);
        });

        $this->renderable(function (Throwable $exception) {
            if (config('app.debug')) {
                return $this->response($exception->getMessage(), 500);
            } else {
                return $this->response('Unexpected error.', 500);
            }
        });
    }
}
