<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    /**
     * Check if admin has the needed privilege.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param string $role
     * @return Closure
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->user()->isAdmin() || $request->user()->role_name === $role) return $next($request);
        abort(403);
    }
}
