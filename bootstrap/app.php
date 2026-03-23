<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'can.manage' => \App\Http\Middleware\CheckCanManage::class,
            'approved' => \App\Http\Middleware\CheckApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 419 - CSRF token expired: redirect to login
        $exceptions->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please refresh and try again.'], 419);
            }

            return redirect()->route('login')
                ->with('warning', __('messages.session_expired'));
        });

        // 500 - Server error: redirect back with toast notification
        $exceptions->renderable(function (\Throwable $e, $request) {
            if ($e instanceof HttpException) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => __('messages.server_error_msg')], 500);
            }

            $fallback = auth()->check() ? route('dashboard') : route('login');

            return redirect()->to(url()->previous($fallback))
                ->with('error', __('messages.server_error_msg'));
        });
    })->create();
