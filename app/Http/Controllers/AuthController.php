<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('actif', true)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['user_id' => $user->id]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        return redirect()->route('login');
    }
}
