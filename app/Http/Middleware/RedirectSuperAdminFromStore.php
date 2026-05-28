<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectSuperAdminFromStore
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && method_exists($user, 'hasPermission') && $user->hasPermission('admin.access')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
