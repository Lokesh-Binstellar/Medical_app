<?php
 
use App\Http\Middleware\AuthTest;
use App\Http\Middleware\CheckApiAccessKey;
use App\Http\Middleware\checkPermission;
use App\Http\Middleware\checkRole;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\TokenValidation;  // Import TokenValidation
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
 
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => checkRole::class,
            'permission' => checkPermission::class,
            'preventHistory'=>PreventBackHistory::class,
            'stateful' => EnsureFrontendRequestsAreStateful::class, 
            'substituteBindings' => SubstituteBindings::class, 
            'tokenValidation' => TokenValidation::class, 
            'check.api.key' =>CheckApiAccessKey::class,
            'authTest'=>AuthTest::class,
            'checkSession.auth'=>CheckSession::class
        ]);
        // $middleware->append(checkRole::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Illuminate\Session\TokenMismatchException $e) {
            return redirect()->back();
        });
    })->create();
 
 
 