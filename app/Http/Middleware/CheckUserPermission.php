<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthenticated.');
        }

        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (! $user->can(Str::snake($permission))) {
                return redirect()->route('profile.show')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }
        }

        return $next($request);
    }
}
