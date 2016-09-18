<?php

namespace App\Http\Middleware;

use Closure;

class MustBeVerifier
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
        if($user && ($user->can('videos.verify'))) {
            return $next($request);
        }
        abort(404);
    }
}
