<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            if ($e instanceof ValidationException) {
                return response()->json(["message" => $e->getMessage(), 'errors' => $e->validator->failed()], 422);
            }

            if($e instanceof NotFoundHttpException){
                return response()->json(["message" => $e->getMessage()], 404);
            }

            if ($e instanceof RepositoryResourceFailedException ) {
                return response()->json(["message" => $e->getMessage()], $e->getCode());
            }
        }

        if ($e instanceof ValidationException) {
            // http form-multipart bug-fix.
            // explanation: throws out false validation errors in unit-tests.
            // but in web/api app instance everything is fine
            if (!App::runningUnitTests()) {
                return response($e->getMessage());
            }
        }

        return parent::render($request, $e);
    }
}
