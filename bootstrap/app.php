<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);

        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'client.auth' => \App\Http\Middleware\ClientAuth::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if ($exception->getStatusCode() !== 419 || ! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'message' => 'Sesi sudah habis. Silakan refresh halaman lalu coba lagi.',
            ], 419);
        });

        $exceptions->respond(function ($response) use (&$exceptions) {
            if ($response->getStatusCode() !== 419) {
                return $response;
            }

            return back()
                ->withInput()
                ->with('error', 'Sesi halaman sudah habis. Refresh halaman lalu kirim ulang form.');
        });
    })->create();
