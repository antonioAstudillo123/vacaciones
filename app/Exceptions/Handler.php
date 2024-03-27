<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof QueryException)
        {

            if ($exception->errorInfo[1] == 1062)
            {
                return response('El correo ingresado ya existe!!', 500);
            }
        }

        return parent::render($request, $exception);
    }
}
