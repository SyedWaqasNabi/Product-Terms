<?php

namespace App\Exceptions;

use Aws\S3\Exception\S3Exception;
use http\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
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
        return $this->getJsonResponse($request, $exception);
    }

    /**
     * Get the json response for the exception.
     *
     * @param  \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJsonResponse($request, $exception)
    {

        $debugEnabled =  config('app.debug');
        $exception = $this->prepareException($exception);
        /*
         * Handle authentication errors thrown using UnauthorizedHttpException.
         */
        if ($exception instanceof UnauthorizedHttpException) {
            if ($debugEnabled) {
                $message = $exception->getMessage();
            } else {
                $message = 'Unauthorized';
            }
        }

        if ($exception instanceof ValidationException) {
            $validationErrors = $exception->validator->errors()->getMessages();

            $validationErrors = array_map(function ($error) {
                return array_map(function ($message) {
                    return $message;
                }, $error);
            }, $validationErrors);

            return response()->json(['errors' => $validationErrors], 422);
        }

        /**
         * Not Found Exception Message
         */
        if ($exception instanceof NotFoundHttpException) {
            if ($debugEnabled == true) {
                $message = $exception->getMessage();
            } else {
                $message = 'Resource not found';
            }
        }

        if ($exception instanceof S3Exception) {
            if ($debugEnabled) {
                $message = $exception->getMessage();
            } else {
                $message = 'Service Unavailable';
            }
        }

        $statusCode = $this->getStatusCode($exception);

        if (! isset($message) && ! ($message = $exception->getMessage())) {
            $message = sprintf('%s', $statusCode);
        }

        $errors = [
            'message' => $message,
            'status_code' => $statusCode,
        ];

        if ($debugEnabled) {
            $errors['exception'] = get_class($exception);
        } else {
            $errors = [
                'message' => $message,
                'status_code' => $statusCode,
            ];
        }
        \Log::critical(
            sprintf('Message: %s. Status: %s,', $exception->getMessage(), $exception->getLine())
        );

        return response()->json(['errors' => $errors], $statusCode);
    }

    /**
     * Get the status code from the exception.
     *
     * @param $exception
     * @return int
     */
    protected function getStatusCode($exception)
    {
        return $this->isHttpException($exception) ? $exception->getStatusCode() : 500;
    }
}
