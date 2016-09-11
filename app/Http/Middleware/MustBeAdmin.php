<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class MustBeAdmin
{
    /**
     * Handle an incoming request: user must be an admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if($user && ($user->isAdmin())) {
            return $next($request);
        }
        abort(404);
    }
}
