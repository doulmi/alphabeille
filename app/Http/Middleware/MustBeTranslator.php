<?php

namespace App\Http\Middleware;

use Closure;

class MustBeTranslator
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
        if($user && ($user->level() >= 3)) {
            return $next($request);
        }
        abort(404);
    }
}
