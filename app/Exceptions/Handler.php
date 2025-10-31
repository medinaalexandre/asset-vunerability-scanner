<?php

namespace App\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->dontReportDuplicates();

        $this->renderable(function (InvalidCredentialsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        });
    }
}
