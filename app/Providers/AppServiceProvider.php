<?php

namespace App\Providers;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::globalMiddleware(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {

                $host = $request->getUri()->getHost();
                Log::info("HTTP OUT => {$request->getMethod()} {$request->getUri()}");

                $start = microtime(true);

                $promise = $handler($request, $options);

                return $promise->then(function (ResponseInterface $response) use ($host, $start) {
                    $elapsed = (int) ((microtime(true) - $start) * 1000); // tempo em ms
                    Log::info("HTTP IN <= {$response->getStatusCode()} from {$host}", [
                        'elapsedTimeMs' => $elapsed,
                    ]);

                    return $response;
                });
            };
        });

    }
}
