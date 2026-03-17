@extends('layouts.app')
@section('title', 'Ventes')

@section('content')
<div class="space-y-5 pt-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ventes</h1>
            <p class="text-gray-500 text-sm">{{ $ventes->total() }} vente{{ $ventes->total() > 1 ? 's' : '' }}</p>
        </div>

        {{-- Bouton export — admin seulement --}}
        @if($currentUser->role === 'admin')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter Excel
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false"
                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-200 z-10 overflow-hidden">
                @foreach([
                    ['journalier',   "Aujourd'hui",   'Ventes du jour'],
                    ['hebdomadaire', 'Cette semaine',  'Ventes de la semaine'],
                    ['mensuel',      'Ce mois',        'Ventes du mois'],
                    ['annuel',       'Cette année',    'Ventes annuelles'],
                ] as [$val, $label, $sub])
                <a href="{{ route('ventes.export', ['periode' => $val]) }}"
                   target="_blank"
                   data-no-loader
                   class="flex flex-col px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-medium text-gray-800">{{ $label }}</span>
                    <span class="text-xs text-gray-400">{{ $sub }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Stats CA — admin seulement --}}
    @if($currentUser->role === 'admin')
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">CA total</div>
            <div class="text-xl font-bold">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">CA ce mois</div>
            <div class="text-xl font-bold text-blue-600">{{ number_format($caMonth, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    @endif

    {{-- Recherche --}}
    <form method="GET" data-no-loader>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
        </div>
    </form>

    {{-- Tableau --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">N°</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Paiement</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Total</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ventes as $vente)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono font-medium">{{ $vente->numero }}</td>
                        <td class="px-4 py-3 text-sm">{{ $vente->client->full_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $vente->mode_paiement_label }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-right text-green-700">
                            {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden lg:table-cell">
                            {{ $vente->date_paiement->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400">Aucune vente enregistrée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $ventes->links() }}

</div>
@endsection
