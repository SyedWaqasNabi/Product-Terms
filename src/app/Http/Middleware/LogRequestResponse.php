<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LogRequestResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('app.request', ['request' => $request->all(), 'header' => $request->headers->all()]);

        return $next($request);
    }

    /**
     * @param $request
     * @param JsonResponse $response
     */
    public function terminate($request, JsonResponse $response)
    {
        Log::info('app.response', ['response' => $response->getContent(), 'header' => $response->headers->all()]);
    }
}
