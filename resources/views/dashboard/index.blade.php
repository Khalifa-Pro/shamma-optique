@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6 pt-4">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
        <p class="text-gray-500 text-sm">Bienvenue, {{ $currentUser->full_name }}</p>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        @if($currentUser->isAdmin())

        {{-- CA total --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Chiffre d'affaires total</span>
                <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</div>
        </div>

        {{-- CA mois --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">CA ce mois</span>
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($caMonth, 0, ',', ' ') }} FCFA</div>
        </div>

        @endif

        {{-- Clients total (tout le monde) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Clients total</span>
                <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#9333ea" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalClients }}</div>
        </div>

        {{-- Factures en attente (tout le monde) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Factures en attente</span>
                <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#ca8a04" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m9-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $facturesAttente }}</div>
        </div>

        {{-- Stock articles — vendeur seulement --}}
        @if(!$currentUser->isAdmin())
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Articles en stock</span>
                <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#4f46e5" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalStock }}</div>
            @if($stockAlertes > 0)
                <div class="mt-2 flex items-center gap-1.5 text-xs text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    {{ $stockAlertes }} article{{ $stockAlertes > 1 ? 's' : '' }} en stock faible
                </div>
            @else
                <div class="mt-2 text-xs text-green-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tous les stocks OK
                </div>
            @endif
        </div>
        @endif

    </div>

    {{-- Chart + actions --}}
    <div class="grid {{ $currentUser->isAdmin() ? 'lg:grid-cols-3' : 'lg:grid-cols-1' }} gap-4">

        @if($currentUser->isAdmin())
        {{-- Graphique CA --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Chiffre d'affaires (6 derniers mois)</h3>
            @php $maxCA = collect($monthlyData)->max('ca') ?: 1; @endphp
            <div class="flex items-end gap-2 h-40">
                @foreach($monthlyData as $month)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-[#1d9bf0] rounded-t transition-all"
                             style="height: {{ max(4, ($month['ca'] / $maxCA) * 130) }}px"
                             title="{{ number_format($month['ca'], 0, ',', ' ') }} FCFA">
                        </div>
                        <span class="text-xs text-gray-400">{{ $month['name'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Actions rapides --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Actions rapides</h3>
            <div class="space-y-2">
                <a href="{{ route('clients.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau client
                </a>
                <a href="{{ route('ordonnances.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle ordonnance
                </a>
                <a href="{{ route('devis.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau devis
                </a>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Devis brouillon</span>
                    <span class="font-semibold">{{ $devisBrouillon }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Devis validés</span>
                    <span class="font-semibold text-blue-600">{{ $devisValide }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Dernières données --}}
    <div class="grid lg:grid-cols-2 gap-4">

        {{-- Derniers clients --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Derniers clients</h3>
                <a href="{{ route('clients.index') }}" class="text-[#1d9bf0] text-sm">Voir tout</a>
            </div>
            <div class="space-y-3">
                @forelse($recentClients as $client)
                    <a href="{{ route('clients.show', $client) }}"
                       class="flex items-center gap-3 hover:bg-gray-50 rounded-lg p-1.5">
                        <div class="w-8 h-8 bg-[#0f2447]/10 rounded-full flex items-center justify-center text-xs font-semibold text-[#0f2447]">
                            {{ substr($client->prenom, 0, 1) }}{{ substr($client->nom, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">{{ $client->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $client->telephone }}</div>
                        </div>
                        <div class="text-xs text-gray-400">{{ $client->created_at->diffForHumans() }}</div>
                    </a>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">Aucun client</p>
                @endforelse
            </div>
        </div>

        {{-- Devis à facturer --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Devis à facturer</h3>
                <a href="{{ route('devis.index', ['statut' => 'valide']) }}" class="text-[#1d9bf0] text-sm">Voir tout</a>
            </div>
            @if($pendingDevis->isEmpty())
                <p class="text-gray-400 text-sm text-center py-4">Aucun devis validé en attente</p>
            @else
                <div class="space-y-3">
                    @foreach($pendingDevis as $d)
                        <a href="{{ route('devis.show', $d) }}"
                           class="flex justify-between items-center hover:bg-gray-50 rounded-lg p-1.5">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $d->numero }}</div>
                                <div class="text-xs text-gray-400">{{ $d->client->full_name }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-sm text-gray-900">
                                    {{ number_format($d->montant_total, 0, ',', ' ') }} FCFA
                                </div>
                                @if($d->magasin)
                                    <div class="text-xs text-gray-400">{{ $d->magasin }}</div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
