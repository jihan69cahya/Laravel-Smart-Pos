<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $routeName = $request->route()->getName();

        $accessibleRoutes = $user->role->relMapping->relMenu->pluck('route')->toArray();

        if (!in_array($routeName, $accessibleRoutes)) {
            abort(404);
        }

        return $next($request);
    }
}
