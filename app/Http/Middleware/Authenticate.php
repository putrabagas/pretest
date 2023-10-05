<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    // public function handle($request, Closure $next, ...$guards)
    // {
    //     if ($this->authenticate($request, $guards) === 'authentication_failed') {
    //         return response()->json([
    //             'errors' => [
    //                 'status' => 401,
    //                 'message' => 'Unauthenticated',
    //             ],
    //         ], 401);
    //     }

    //     return $next($request);
    // }

    // protected function redirectTo($request): Response
    // {
    //     if (! $request->expectsJson()) {
    //         return response([
    //             'code' => 401,
    //             'errors' => [
    //                 'message' => 'Please login before access this menu'
    //                 ]
    //         ], 401);
    //     }
    // }
}
