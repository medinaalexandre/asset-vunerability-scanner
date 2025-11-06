<?php

use App\Exceptions\BusinessRuleException;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Middleware\EnsureJsonResponseMiddleware;
use ChaseConey\LaravelDatadogHelper\Middleware\LaravelDatadogMiddleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            EnsureJsonResponseMiddleware::class,
            LaravelDatadogMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            BusinessRuleException::class
        ])
            ->shouldRenderJsonWhen(fn (Request $request) => $request->is('api/*') || $request->expectsJson())
            ->renderable(function (Throwable $e, $request) {
                if ($e instanceof InvalidCredentialsException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], Response::HTTP_UNAUTHORIZED);
                }

                if ($e instanceof BusinessRuleException) {
                    $statusCode = $e->getCode() ?? Response::HTTP_BAD_REQUEST;

                    return response()->json([
                        'message' => $e->getMessage(),
                        'status' => 'error'
                    ], $statusCode);
                }

                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], Response::HTTP_NOT_FOUND);
                }

                return null;
            });
    })
    ->create();
