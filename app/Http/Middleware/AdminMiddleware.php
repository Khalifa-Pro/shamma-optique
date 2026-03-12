<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = view()->getShared()['currentUser'] ?? null;
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux administrateurs.');
        }
        return $next($request);
    }
}
