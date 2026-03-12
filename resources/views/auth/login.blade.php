<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Shamma Optique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen flex">
    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-[#0f2447] flex-col items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            @for($i = 1; $i <= 6; $i++)
                <div class="absolute rounded-full border border-white"
                     style="width:{{ $i * 120 }}px;height:{{ $i * 120 }}px;top:50%;left:50%;transform:translate(-50%,-50%)"></div>
            @endfor
        </div>
        <div class="relative z-10 text-center">
            <div class="w-40 h-40 bg-[#ffff] rounded-2xl flex items-center justify-center mx-auto mb-6">
                <img src="./asset/img/logo.jpg" alt="" width="200" height="60">
            </div>
            <h1 class="text-3xl font-bold text-white mb-3">Shamma Optique</h1>
            <p class="text-white/60 text-lg mb-8">Plateforme de gestion optique</p>
            <div class="grid grid-cols-2 gap-4 text-left">
                @foreach(['Gestion clients', 'Ordonnances', 'Devis & Factures', 'Suivi des ventes'] as $feat)
                    <div class="flex items-center gap-2 text-white/70 text-sm">
                        <div class="w-5 h-5 bg-[#1d9bf0]/20 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="#1d9bf0" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        {{ $feat }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                <div class="w-60 h-60 bg-[#ffff] rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <img src="./asset/img/logo.jpg" alt="" width="200" height="60">
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Connexion</h2>
            <p class="text-gray-500 text-sm mb-8">Entrez vos identifiants pour accéder à la plateforme</p>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" x-data="{showPwd: false}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="exemple@shammaoptique.cv"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] focus:border-transparent"
                           required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                    <div class="relative">
                        <input :type="showPwd ? 'text' : 'password'" name="password"
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] focus:border-transparent pr-10"
                               required>
                        <button type="button" @click="showPwd = !showPwd"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPwd" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPwd" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-[#0f2447] text-white font-semibold rounded-lg hover:bg-[#1a3a6b] transition-colors">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
