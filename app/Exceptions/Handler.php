<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     *
     * @param Throwable $exception
     * @return JsonResponse|Response
     * @throws Throwable
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function render($request, Throwable $exception)
    {
        if (!$this->isApiCall($request) && !$exception instanceof ApiException) {
            return parent::render($request, $exception);
        }

        return $this->getJsonResponseForException($exception);
    }

    /**
     * Check if it is API call.
     *
     * @param Request $request
     * @return boolean
     */
    protected function isApiCall(Request $request): bool
    {
        return strpos($request->getUri(), '/api') !== false;
    }

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Exception $exception
     * @return JsonResponse
     */
    protected function getJsonResponseForException(Exception $exception): JsonResponse
    {
        $message = 'Something went wrong';
        if (App::environment('local')) {
            $message = $exception->getMessage();
        }
        $statusCode = 500;
        switch (true) {
            case $exception instanceof ApiException:
                $message = $exception->getErrors();
                $statusCode = $exception->getStatusCode();
                break;
            case $exception instanceof HttpExceptionInterface:
                $message = $exception->getMessage();
                $statusCode = $exception->getStatusCode();
                break;
            case $exception instanceof ModelNotFoundException:
                $message = $exception->getMessage();
                $statusCode = 404;
                break;
            case $exception instanceof AuthenticationException:
            case $exception instanceof UnauthorizedHttpException:
                $message = $exception->getMessage();
                $statusCode = 401;
                break;
            case $exception instanceof AccessDeniedHttpException:
                $message = $exception->getMessage();
                $statusCode = 403;
                break;
        }
        return response()->json(['error' => $message], $statusCode);
    }
}
