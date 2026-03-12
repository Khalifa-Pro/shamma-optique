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

        {{-- CA total --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Chiffre d'affaires total</span>
                <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"/>
                    </svg>
                </div>
            </div>

            <div class="text-2xl font-bold text-gray-900">
                {{ number_format($totalCA, 0, ',', ' ') }} FCFA
            </div>
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

            <div class="text-2xl font-bold text-gray-900">
                {{ number_format($caMonth, 0, ',', ' ') }} FCFA
            </div>
        </div>

        {{-- Clients --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Clients total</span>
                <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V9H2v11h5m10 0a4 4 0 10-8 0m8 0H9"/>
                    </svg>
                </div>
            </div>

            <div class="text-2xl font-bold text-gray-900">
                {{ $totalClients }}
            </div>
        </div>

        {{-- Factures --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">Factures en attente</span>
                <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m9-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="text-2xl font-bold text-gray-900">
                {{ $facturesAttente }}
            </div>
        </div>

    </div>

    {{-- Chart + actions --}}
    <div class="grid lg:grid-cols-3 gap-4">

        {{-- Graphique CA --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">

            <h3 class="font-semibold text-gray-900 mb-4">
                Chiffre d'affaires (6 derniers mois)
            </h3>

            @php
                $maxCA = collect($monthlyData)->max('ca') ?: 1;
            @endphp

            <div class="flex items-end gap-2 h-40">

                @foreach($monthlyData as $month)

                    <div class="flex-1 flex flex-col items-center gap-1">

                        <div class="w-full bg-[#1d9bf0] rounded-t transition-all"
                             style="height: {{ max(4, ($month['ca'] / $maxCA) * 130) }}px"
                             title="{{ number_format($month['ca'], 0, ',', ' ') }} FCFA">
                        </div>

                        <span class="text-xs text-gray-400">
                            {{ $month['name'] }}
                        </span>

                    </div>

                @endforeach

            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">

            <h3 class="font-semibold text-gray-900 mb-4">
                Actions rapides
            </h3>

            <div class="space-y-2">

                <a href="{{ route('clients.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">

                    Nouveau client
                </a>

                <a href="{{ route('ordonnances.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">

                    Nouvelle ordonnance
                </a>

                <a href="{{ route('devis.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">

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

                @foreach($recentClients as $client)

                    <a href="{{ route('clients.show', $client) }}"
                       class="flex items-center gap-3 hover:bg-gray-50 rounded-lg p-1.5">

                        <div class="w-8 h-8 bg-[#0f2447]/10 rounded-full flex items-center justify-center text-xs font-semibold text-[#0f2447]">
                            {{ substr($client->prenom,0,1) }}{{ substr($client->nom,0,1) }}
                        </div>

                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $client->full_name }}
                            </div>

                            <div class="text-xs text-gray-400">
                                {{ $client->telephone }}
                            </div>
                        </div>

                        <div class="text-xs text-gray-400">
                            {{ $client->created_at->diffForHumans() }}
                        </div>

                    </a>

                @endforeach

            </div>

        </div>

        {{-- Devis à facturer --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">

            <div class="flex justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Devis à facturer</h3>
                <a href="{{ route('devis.index', ['statut'=>'valide']) }}" class="text-[#1d9bf0] text-sm">
                    Voir tout
                </a>
            </div>

            @if($pendingDevis->isEmpty())

                <p class="text-gray-400 text-sm text-center py-4">
                    Aucun devis validé en attente
                </p>

            @else

                <div class="space-y-3">

                    @foreach($pendingDevis as $d)

                        <a href="{{ route('devis.show', $d) }}"
                           class="flex justify-between hover:bg-gray-50 rounded-lg p-1.5">

                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $d->numero }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    {{ $d->client->full_name }}
                                </div>
                            </div>

                            <div class="font-semibold text-sm text-gray-900">
                                {{ number_format($d->montant_total, 0, ',', ' ') }} FCFA
                            </div>

                        </a>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</div>
@endsection
