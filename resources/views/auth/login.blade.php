<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Shamma Optique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen flex">

    {{-- Panneau gauche --}}
    <div class="hidden lg:flex lg:w-1/2 bg-[#0f2447] flex-col items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            @for($i = 1; $i <= 6; $i++)
                <div class="absolute rounded-full border border-white"
                     style="width:{{ $i * 120 }}px;height:{{ $i * 120 }}px;top:50%;left:50%;transform:translate(-50%,-50%)"></div>
            @endfor
        </div>
        <div class="relative z-10 text-center">
            <div class="w-40 h-40 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 overflow-hidden">
                <img src="{{ asset('asset/img/SHAMMA_OPTIQUE_LOGO.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
            </div>
            <h1 class="text-3xl font-bold text-white mb-3">Shamma Optique</h1>
            <p class="text-white/60 text-lg mb-8">Plateforme de gestion optique</p>
            <div class="grid grid-cols-2 gap-4 text-left">
                @foreach(['Gestion clients', 'Ordonnances', 'Devis & Factures', 'Suivi des ventes'] as $feat)
                    <div class="flex items-center gap-2 text-white/70 text-sm">
                        <div class="w-5 h-5 bg-[#1d9bf0]/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="#1d9bf0" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        {{ $feat }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Panneau droit --}}
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
            <div class="lg:hidden flex justify-center mb-8">
                <div class="w-32 h-32 bg-white rounded-2xl flex items-center justify-center shadow overflow-hidden">
                    <img src="{{ asset('asset/img/SHAMMA_OPTIQUE_LOGO.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Connexion</h2>
            <p class="text-gray-500 text-sm mb-8">Entrez vos identifiants pour accéder à la plateforme</p>

            {{-- Erreur --}}
            @if($errors->any())
                @php
                    $msg     = $errors->first();
                    $bloque  = str_contains($msg, 'bloqué') || str_contains($msg, 'Trop de');
                    $warning = str_contains($msg, 'tentative');
                @endphp
                <div class="mb-5 px-4 py-3 rounded-lg text-sm flex items-start gap-3
                    {{ $bloque ? 'bg-red-50 border border-red-300 text-red-800' : ($warning ? 'bg-orange-50 border border-orange-300 text-orange-800' : 'bg-red-50 border border-red-200 text-red-700') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0.5">
                        @if($bloque)
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-9V7a5 5 0 00-10 0v4a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H4V7a3 3 0 016 0v4"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        @endif
                    </svg>
                    <span>{{ $msg }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}"
                x-data="{ showPwd: false, countdown: {{ $minutesRestantes * 60 }} }"
                x-init="{{ $estBloque ? 'startCountdown()' : '' }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="exemple@shammaoptique.com"
                        @if($estBloque) disabled @endif
                        class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]
                                {{ $estBloque ? 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed' : 'border-gray-200' }}
                                {{ $errors->any() && !$estBloque ? 'border-red-300' : '' }}"
                        required autocomplete="email">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <input :type="showPwd ? 'text' : 'password'" name="password"
                            placeholder="••••••••"
                            @if($estBloque) disabled @endif
                            class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] pr-10
                                    {{ $estBloque ? 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed' : 'border-gray-200' }}
                                    {{ $errors->any() && !$estBloque ? 'border-red-300' : '' }}"
                            required autocomplete="current-password">
                        @if(!$estBloque)
                        <button type="button" @click="showPwd = !showPwd"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPwd" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPwd" x-cloak xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Bouton désactivé si bloqué --}}
                @if($estBloque)
                    <button type="button" disabled
                            class="w-full py-2.5 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span x-text="countdown > 0 ? 'Bloqué — ' + formatTime(countdown) : 'Veuillez recharger la page'"></span>
                    </button>

                    {{-- Barre de progression du décompte --}}
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Déblocage automatique dans</span>
                            <span x-text="formatTime(countdown)"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-red-400 h-1.5 rounded-full transition-all duration-1000"
                                :style="`width: ${(countdown / {{ $minutesRestantes * 60 }}) * 100}%`"></div>
                        </div>
                    </div>

                @else
                    <button type="submit"
                            class="w-full py-2.5 bg-[#0f2447] text-white font-semibold rounded-lg hover:bg-[#1a3a6b] transition-colors">
                        Se connecter
                    </button>
                @endif

            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                Shamma Optique &copy; {{ date('Y') }} — Tous droits réservés
            </p>
        </div>
    </div>
</div>
@if($estBloque)
<script>
    function startCountdown() {
        const timer = setInterval(() => {
            if (this.countdown <= 0) {
                clearInterval(timer);
                window.location.reload();  // Recharger quand le blocage expire
                return;
            }
            this.countdown--;
        }, 1000);
    }

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m}m ${s.toString().padStart(2, '0')}s`;
    }
</script>
@endif
{{-- Loader connexion --}}
<div id="login-loader" class="hidden fixed inset-0 bg-white/90 z-50 flex flex-col items-center justify-center gap-4">
    <div style="width:56px;height:56px;border:4px solid #e5e7eb;border-top-color:#0f2447;border-right-color:#1d9bf0;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
    <div class="text-center">
        <div class="text-[#0f2447] font-semibold">Connexion en cours...</div>
        <div class="text-gray-400 text-sm mt-1">Vérification de vos identifiants</div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('login-loader').classList.remove('hidden');
    });
</script>
</body>
</html>
