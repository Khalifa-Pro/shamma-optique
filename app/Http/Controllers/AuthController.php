<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LoginAttempt;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $email     = $request->old('email', '');
        $ip        = $request->ip();
        $estBloque = $email ? LoginAttempt::estBloque($email, $ip) : false;

        // Récupérer le temps restant si bloqué
        $minutesRestantes = 0;
        if ($estBloque) {
            $blocage = LoginAttempt::getBlocage($email, $ip);
            $minutesRestantes = max(1, now()->diffInMinutes($blocage->bloque_jusqu_au, false));
        }

        return view('auth.login', compact('estBloque', 'minutesRestantes'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $email = strtolower(trim($request->email));
        $ip    = $request->ip();

        // ── 1. Vérifier si l'IP ou l'email est bloqué ────
        if (LoginAttempt::estBloque($email, $ip)) {
            $blocage = LoginAttempt::getBlocage($email, $ip);
            $restant = now()->diffInMinutes($blocage->bloque_jusqu_au, false);
            $restant = max(1, $restant);

            return back()
                ->withErrors([
                    'email' => "Compte temporairement bloqué suite à trop de tentatives. "
                             . "Réessayez dans {$restant} minute(s)."
                ])
                ->withInput(['email' => $request->email]);
        }

        // ── 2. Vérifier les identifiants ─────────────────
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            // Vérifier si le compte est actif
            if (!$user->actif) {
                LoginAttempt::enregistrer($email, $ip, false);
                return back()
                    ->withErrors(['email' => 'Ce compte est désactivé. Contactez un administrateur.'])
                    ->withInput(['email' => $request->email]);
            }

            // ✅ Succès — nettoyer les tentatives et connecter
            LoginAttempt::reinitialiser($email, $ip);
            session([
                'user_id'    => $user->id,
                'user_email' => $user->email,
                'user_role'  => $user->role,
            ]);

            return redirect()->route('dashboard');
        }

        // ── 3. Échec — compter et éventuellement bloquer ─
        $tentatives = LoginAttempt::compterTentatives($email, $ip);
        $tentatives++; // inclure la tentative actuelle

        $restantes = LoginAttempt::MAX_TENTATIVES - $tentatives;
        $bloquer   = $tentatives >= LoginAttempt::MAX_TENTATIVES;

        LoginAttempt::enregistrer($email, $ip, false, $bloquer);

        if ($bloquer) {
            return back()
                ->withErrors([
                    'email' => "Trop de tentatives échouées. Compte bloqué pendant "
                             . LoginAttempt::BLOCAGE_MINUTES . " minutes."
                ])
                ->withInput(['email' => $request->email]);
        }

        $msg = $restantes > 0
            ? "Email ou mot de passe incorrect. Il vous reste {$restantes} tentative(s)."
            : "Email ou mot de passe incorrect.";

        return back()
            ->withErrors(['email' => $msg])
            ->withInput(['email' => $request->email]);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
