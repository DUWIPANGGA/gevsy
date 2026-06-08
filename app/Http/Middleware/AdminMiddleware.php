<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Full access for super_admin / admin roles
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return $next($request);
        }

        // Allow if user has any admin_access_* permission
        $permissions = $user->getAllPermissions()->pluck('name');
        foreach ($permissions as $perm) {
            if (str_starts_with($perm, 'admin_access_')) {
                return $next($request);
            }
        }

        abort(403, 'Akses admin ditolak.');
    }
}
