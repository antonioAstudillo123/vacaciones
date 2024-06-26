<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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


        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->view('error', [], 500);
        });
    }

    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof QueryException)
        {

            if ($exception->errorInfo[1] == 1062)
            {
                return response('El correo ingresado ya existe!!', 500);
            }else if($exception->errorInfo[1] == 1054){
                return response('Tuvimos problemas en el servidor!!!', 500);
            }
        }

        return parent::render($request, $exception);
    }


}
