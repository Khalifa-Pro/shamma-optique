<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user || !$user->actif) {
            session()->forget('user_id');
            return redirect()->route('login');
        }

        // Share user with all views
        view()->share('currentUser', $user);

        return $next($request);
    }
}
