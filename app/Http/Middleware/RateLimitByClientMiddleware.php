<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitByClientMiddleware
{

    protected RateLimiter $rateLimit;

    public function __construct(RateLimiter $rateLimit)
    {
        $this->rateLimit = $rateLimit;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-Client-Id', $request->ip());

        $maxAttempts = 60;
        $decayMinutes = 1;

        if ($this->rateLimit->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->rateLimit->availableIn($key);

            return response()->json([
                'message' => 'Too many requests.',
                'retry_after' => $seconds,
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $this->rateLimit->hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
