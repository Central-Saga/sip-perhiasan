<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Allow only users with admin-side roles to access.
     * Permitted roles: Admin, Owner, Manager, Staff.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasAnyRole(['Admin', 'Owner', 'Manager', 'Staff'])) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Akses ditolak untuk area admin.');
    }
}

