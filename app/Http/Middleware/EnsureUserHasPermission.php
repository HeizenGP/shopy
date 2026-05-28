<?php

namespace App\Http\Middleware;

use Closure;
use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        /** @var UserEloquent|null $user */
        $user = Auth::user();

        if (!$user || !method_exists($user, 'hasPermission') || !$user->hasPermission($permissions)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
