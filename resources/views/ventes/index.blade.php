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
                    ['journalier',   "Aujourd'hui",  'Ventes du jour'],
                    ['hebdomadaire', 'Cette semaine', 'Ventes de la semaine'],
                    ['mensuel',      'Ce mois',       'Ventes du mois'],
                    ['annuel',       'Cette année',   'Ventes annuelles'],
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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">CA total</div>
            <div class="text-xl font-bold text-gray-900">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">CA ce mois</div>
            <div class="text-xl font-bold text-blue-600">{{ number_format($caMonth, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Part clients (total)</div>
            <div class="text-xl font-bold text-gray-900">{{ number_format($totalPartClient, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Part assurance (total)</div>
            <div class="text-xl font-bold text-indigo-600">{{ number_format($totalPartAssurance, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    @endif

    {{-- Recherche --}}
    <form method="GET" data-no-loader>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                 xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Rechercher par numéro, client..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
        </div>
    </form>

    {{-- Tableau --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">N° Vente</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Paiement client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">Paiement assurance</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">Part client</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden xl:table-cell">Part assurance</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Total</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ventes as $vente)
                    <tr class="hover:bg-gray-50">

                        {{-- N° --}}
                        <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800">
                            {{ $vente->numero }}
                        </td>

                        {{-- Client --}}
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-800">{{ $vente->client->full_name }}</div>
                            @if($vente->client->mutuelle)
                                <div class="text-xs text-gray-400">{{ $vente->client->mutuelle }}</div>
                            @endif
                        </td>

                        {{-- Mode paiement client --}}
                        <td class="px-4 py-3 hidden md:table-cell">
                            @php
                                $mode = $vente->mode_paiement;
                                [$icon, $style] = match(true) {
                                    $mode === 'especes'                     => ['💵', 'bg-green-50 text-green-700'],
                                    $mode === 'carte'                       => ['💳', 'bg-blue-50 text-blue-700'],
                                    $mode === 'virement'                    => ['🏦', 'bg-indigo-50 text-indigo-700'],
                                    $mode === 'cheque'                      => ['📄', 'bg-yellow-50 text-yellow-700'],
                                    str_starts_with($mode, 'mobile_wave')  => ['🔵', 'bg-cyan-50 text-cyan-700'],
                                    str_starts_with($mode, 'mobile_orange')=> ['🟠', 'bg-orange-50 text-orange-700'],
                                    str_starts_with($mode, 'mobile_mtn')   => ['🟡', 'bg-yellow-50 text-yellow-800'],
                                    default                                 => ['💰', 'bg-gray-50 text-gray-700'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $style }}">
                                {{ $icon }} {{ $vente->mode_paiement_client_label }}
                            </span>
                        </td>

                        {{-- Mode paiement assurance --}}
                        <td class="px-4 py-3 hidden lg:table-cell">
                            @if($vente->mode_paiement_assurance && $vente->part_assurance > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                    🏥
                                    {{ match($vente->mode_paiement_assurance) {
                                        'mutuelle' => 'Mutuelle',
                                        'virement' => 'Virement',
                                        'autre'    => 'Autre',
                                        default    => $vente->mode_paiement_assurance,
                                    } }}
                                </span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Part client --}}
                        <td class="px-4 py-3 text-sm text-right hidden lg:table-cell">
                            <span class="text-gray-700">
                                {{ number_format($vente->part_client, 0, ',', ' ') }} FCFA
                            </span>
                        </td>

                        {{-- Part assurance --}}
                        <td class="px-4 py-3 text-sm text-right hidden xl:table-cell">
                            @if($vente->part_assurance > 0)
                                <span class="text-indigo-600 font-medium">
                                    {{ number_format($vente->part_assurance, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Total --}}
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-semibold text-green-700">
                                {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                            </span>
                            {{-- Badge mixte sur mobile --}}
                            @if($vente->part_assurance > 0)
                                <div class="text-xs text-purple-500 mt-0.5 lg:hidden">
                                    + Assurance
                                </div>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">
                            {{ $vente->date_paiement->format('d/m/Y') }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            Aucune vente enregistrée
                        </td>
                    </tr>
                @endforelse
            </tbody>

            {{-- Pied de tableau avec totaux — admin seulement --}}
            @if($currentUser->role === 'admin' && $ventes->count() > 0)
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50">
                    <td colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-600">
                        Total — {{ $ventes->count() }} vente(s) affichée(s)
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-gray-700 hidden lg:table-cell">
                        {{ number_format($ventes->sum('part_client'), 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-indigo-600 hidden xl:table-cell">
                        {{ number_format($ventes->sum('part_assurance'), 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-green-700">
                        {{ number_format($ventes->sum('montant_total'), 0, ',', ' ') }} FCFA
                    </td>
                    <td class="hidden md:table-cell"></td>
                </tr>
            </tfoot>
            @endif

        </table>
    </div>

    {{ $ventes->links() }}

</div>
@endsection
