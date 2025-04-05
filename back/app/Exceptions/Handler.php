<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Personalizar el mensaje cuando el usuario no está autenticado
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'error' => '⚠️ Debes iniciar sesión para acceder a esta ruta.'
        ], 401);
    }
}
