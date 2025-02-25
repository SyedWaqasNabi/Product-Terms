<?php

namespace App\Http\Middleware;

use \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Closure;

class HttpBasicAuth extends AuthenticateWithBasicAuth
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
        $authenticationHasPassed = false;

        if ($request->header('PHP_AUTH_USER', null) && $request->header('PHP_AUTH_PW', null)) {
            $username = $request->header('PHP_AUTH_USER');
            $password = $request->header('PHP_AUTH_PW');

            if ($username === env('BASIC_AUTH_USERNAME') && $password === env('BASIC_AUTH_PASSWORD')) {
                $authenticationHasPassed = true;
            }
        }

        if ($authenticationHasPassed === false) {
            \Log::info(sprintf(
                'Invalid credentials. Path: %s. status: %s.',
                $request->getPathInfo(),
                401
            ));
            return response()->make('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}
