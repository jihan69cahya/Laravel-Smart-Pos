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

        // Ambil nama route sekarang
        $currentRoute = $request->route()->getName();
        // contoh: manajemen.menu.index

        // Hanya cek jika route berakhiran .index
        if (str_ends_with($currentRoute, '.index')) {

            // Ambil semua route menu dari user
            $allowedRoutes = $user->relRole
                ->relMapping
                ->pluck('relMenu.route')
                ->toArray();

            // Jika route sekarang tidak masuk daftar menu user → blok
            if (!in_array($currentRoute, $allowedRoutes)) {
                abort(403, 'Anda tidak memiliki akses untuk membuka menu ini.');
            }
        }

        return $next($request);
    }
}
