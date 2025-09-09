<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PelangganAccess
{
    /**
     * Allow only users with Pelanggan role to access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('Pelanggan')) {
            return $next($request);
        }

        // Non-pelanggan diarahkan ke dashboard admin
        return redirect()->route('dashboard')->with('error', 'Akses khusus pelanggan.');
    }
}

