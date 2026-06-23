<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register admin middleware alias
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
            'parent' => \App\Http\Middleware\ParentMiddleware::class,
  'check.license' => \App\Http\Middleware\CheckLicense::class, 
     ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();