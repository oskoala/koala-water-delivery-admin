<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DelivererCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user->is_deliverer) {
            abort(422, "您没有配送权限");
        }
        return $next($request);
    }
}
