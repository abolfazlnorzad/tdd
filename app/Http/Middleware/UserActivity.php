<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            cache()->set("user-{$user->id}-status", "online", 10);
        }
        return $next($request);
    }
}
