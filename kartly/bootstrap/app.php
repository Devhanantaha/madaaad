<?php

use Core\Exceptions\PluginException;
use Core\Exceptions\CurrencyException;
use Illuminate\Foundation\Application;
use Core\Exceptions\ThemeRequiredPluginException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ===== Global middleware  =====
        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);

        // ===== Groups middleware =====
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ===== Route middleware =====
        $middleware->alias([
            'auth'             => \App\Http\Middleware\Authenticate::class,
            'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'     => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'              => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'           => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'demo'             => \App\Http\Middleware\DemoApp::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (PostTooLargeException $e, $request) {
            return response()->view('core::base.errors.500', [
                'message' => 'File size is too Large',
                'title'   => 'Large File Size',
                'route'   => '#',
            ], 500);
        });

        $exceptions->render(function (PluginException $e, $request) {
            return response()->view('core::base.errors.plugin_exception', [
                'message' => $e->getMessage(),
                'title'   => 'Large File Size',
                'route'   => '#',
            ], 500);
        });

        $exceptions->render(function (ThemeRequiredPluginException $e, $request) {
            return response()->view('core::base.errors.theme_required_plugin_failed', [
                'message' => $e->getMessage(),
                'title'   => 'Large File Size',
                'route'   => '#',
            ], 500);
        });

        $exceptions->render(function (CurrencyException $e, $request) {
            return response()->view('core::base.errors.currency_error', [
                'message' => $e->getMessage(),
                'title'   => 'Large File Size',
                'route'   => '#',
            ], 500);
        });

        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);

        // === reportable (was $this->reportable(...)) ===
        $exceptions->report(function (Throwable $e) {
            // custom reporting…
        });
    })->create();
