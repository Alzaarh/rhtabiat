<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $admin = $request->user();
        if ($admin->isAdmin() || $admin->role_name === $role) {
            return $next($request);
        }
        abort(403);
    }
}
