<?php

use App\Http\Middleware\AssignRequestId;
use App\Http\Middleware\LogHttpInboundRequests;
use App\Http\Middleware\RateLimitByClientMiddleware;
use App\Http\Middleware\ValidateClientIdHeader;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend([
            AssignRequestId::class,
            LogHttpInboundRequests::class
        ]);

        $middleware->alias([
            'validate.client.id' => ValidateClientIdHeader::class
        ]);

        $middleware->appendToGroup('api', [
            ValidateClientIdHeader::class,
            RateLimitByClientMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
