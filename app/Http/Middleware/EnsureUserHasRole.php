<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Restrict a route to one or more roles, e.g.:
     *   Route::get(...)->middleware('role:Admin,SuperAdmin');
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $allowed = array_map(
            fn (string $role) => Role::from($role),
            $roles
        );

        if (! in_array($user->role, $allowed, true)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
