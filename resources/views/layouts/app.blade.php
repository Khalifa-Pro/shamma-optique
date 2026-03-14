<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shamma Optique') — Gestion Optique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900">

<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen" x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:relative inset-y-0 left-0 z-30 flex flex-col w-64 bg-[#0f2447] text-white transition-transform duration-300 ease-in-out">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                <img src="{{ asset('asset/img/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <div class="font-semibold">Shamma Optique</div>
                <div class="text-white/50 text-xs">Gestion Optique</div>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-white/70 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3">

            {{-- Section principale --}}
            <div class="text-white/40 text-xs uppercase tracking-wider px-3 mb-2">Principal</div>

            @php
                function navLink($route, $label, $icon, $match = null) {
                    $match = $match ?? str_replace('.index', '.*', $route);
                    $isActive = request()->routeIs($match);
                    $base = 'flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 transition-colors text-sm font-medium';
                    $active = 'bg-[#1d9bf0] text-white';
                    $inactive = 'text-white/70 hover:bg-white/10 hover:text-white';
                    return [$isActive ? "$base $active" : "$base $inactive", $isActive];
                }
            @endphp

            {{-- Dashboard --}}
            @php [$cls] = navLink('dashboard', '', '', 'dashboard'); @endphp
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Tableau de bord
            </a>

            {{-- Clients --}}
            @php [$cls] = navLink('clients.index', '', '', 'clients.*'); @endphp
            <a href="{{ route('clients.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Clients
            </a>

            {{-- Ordonnances --}}
            @php [$cls] = navLink('ordonnances.index', '', '', 'ordonnances.*'); @endphp
            <a href="{{ route('ordonnances.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Ordonnances
            </a>

            {{-- Séparateur section Commerce --}}
            <div class="text-white/40 text-xs uppercase tracking-wider px-3 mt-4 mb-2">Commerce</div>

            {{-- Devis --}}
            @php [$cls] = navLink('devis.index', '', '', 'devis.*'); @endphp
            <a href="{{ route('devis.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Devis
            </a>

            {{-- Factures --}}
            @php
                [$cls] = navLink('factures.index', '', '', 'factures.*');
                $facturesAttentes = \App\Models\Facture::where('statut','en_attente')->count();
            @endphp
            <a href="{{ route('factures.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
                Factures
                @if($facturesAttentes > 0)
                    <span class="ml-auto bg-yellow-400/80 text-yellow-900 text-xs px-1.5 py-0.5 rounded-full font-semibold">{{ $facturesAttentes }}</span>
                @endif
            </a>

            {{-- Ventes --}}
            @php [$cls] = navLink('ventes.index', '', '', 'ventes.*'); @endphp
            <a href="{{ route('ventes.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Ventes
            </a>

            {{-- Section Admin --}}
            @if($currentUser->isAdmin())
                <div class="text-white/40 text-xs uppercase tracking-wider px-3 mt-4 mb-2">Administration</div>

                {{-- Stock --}}
                @php
                    [$cls] = navLink('produits.index', '', '', 'produits.*');
                    $stockAlertes = \App\Models\Produit::whereColumn('stock_actuel','<=','stock_minimum')->count();
                @endphp
                <a href="{{ route('produits.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Stock
                    @if($stockAlertes > 0)
                        <span class="ml-auto bg-orange-400/80 text-orange-900 text-xs px-1.5 py-0.5 rounded-full font-semibold">{{ $stockAlertes }}</span>
                    @endif
                </a>

                {{-- Utilisateurs --}}
                @php [$cls] = navLink('users.index', '', '', 'users.*'); @endphp
                <a href="{{ route('users.index') }}" @click="sidebarOpen = false" class="{{ $cls }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Utilisateurs
                </a>
            @endif

        </nav>

        {{-- User footer --}}
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#1d9bf0]/20 rounded-full flex items-center justify-center">
                    <span class="text-[#1d9bf0] text-xs font-semibold">
                        {{ substr($currentUser->prenom, 0, 1) }}{{ substr($currentUser->nom, 0, 1) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-sm font-medium truncate">{{ $currentUser->full_name }}</div>
                    <div class="text-white/50 text-xs">{{ $currentUser->isAdmin() ? 'Administrateur' : 'Vendeur' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-white transition-colors" title="Se déconnecter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar mobile --}}
        <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-3 lg:hidden">
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-semibold text-[#0f2447]">Shamma Optique</span>
        </header>

        {{-- Alerts --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-4 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6 pt-2">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
