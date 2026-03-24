@extends('layouts.app')
@section('title', 'Ventes')

@section('content')
<div class="space-y-5 pt-4">

    {{-- ─── En-tête ─────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ventes</h1>
            <p class="text-gray-500 text-sm">
                {{ $ventes->total() }} vente{{ $ventes->total() > 1 ? 's' : '' }}
                <span class="text-gray-400">
                    — du {{ $debut->format('d/m/Y') }} au {{ $fin->format('d/m/Y') }}
                </span>
            </p>
        </div>

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
                   target="_blank" data-no-loader
                   class="flex flex-col px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-medium text-gray-800">{{ $label }}</span>
                    <span class="text-xs text-gray-400">{{ $sub }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ─── Filtres date ────────────────────────────────── --}}
    <form method="GET" data-no-loader
          class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex flex-wrap items-end gap-3">

            {{-- Raccourcis rapides --}}
            <div class="flex flex-wrap gap-2">
                @foreach([
                    ['label' => "Aujourd'hui",   'debut' => now()->format('Y-m-d'),                    'fin' => now()->format('Y-m-d')],
                    ['label' => 'Cette semaine',  'debut' => now()->startOfWeek()->format('Y-m-d'),     'fin' => now()->endOfWeek()->format('Y-m-d')],
                    ['label' => 'Ce mois',        'debut' => now()->startOfMonth()->format('Y-m-d'),    'fin' => now()->endOfMonth()->format('Y-m-d')],
                    ['label' => 'Cette année',    'debut' => now()->startOfYear()->format('Y-m-d'),     'fin' => now()->endOfYear()->format('Y-m-d')],
                ] as $shortcut)
                <a href="{{ route('ventes.index', array_merge(request()->except(['date_debut','date_fin','page']), ['date_debut' => $shortcut['debut'], 'date_fin' => $shortcut['fin']])) }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-lg border transition
                          {{ $dateDebut === $shortcut['debut'] && $dateFin === $shortcut['fin']
                             ? 'bg-blue-600 text-white border-blue-600'
                             : 'bg-white text-gray-600 border-gray-200 hover:border-blue-400 hover:text-blue-600' }}">
                    {{ $shortcut['label'] }}
                </a>
                @endforeach
            </div>

            {{-- Séparateur vertical --}}
            <div class="hidden sm:block w-px h-8 bg-gray-200"></div>

            {{-- Intervalle personnalisé --}}
            <div class="flex items-end gap-2 flex-wrap">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Du</label>
                    <input type="date" name="date_debut"
                           value="{{ $dateDebut ?? $debut->format('Y-m-d') }}"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Au</label>
                    <input type="date" name="date_fin"
                           value="{{ $dateFin ?? $fin->format('Y-m-d') }}"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                </div>
                @if($search)
                <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Filtrer
                </button>
                @if($dateDebut || $dateFin)
                <a href="{{ route('ventes.index', $search ? ['search' => $search] : []) }}"
                   class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:border-gray-300 transition">
                    ✕ Reset
                </a>
                @endif
            </div>
        </div>
    </form>

    {{-- ─── Stats CA filtrées ───────────────────────────── --}}
    @if($currentUser->role === 'admin')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">CA sur la période</div>
            <div class="text-xl font-bold text-gray-900">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</div>
            <div class="text-xs text-gray-400 mt-1">{{ $debut->format('d/m') }} → {{ $fin->format('d/m/Y') }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Nombre de ventes</div>
            <div class="text-xl font-bold text-blue-600">{{ $ventes->total() }}</div>
            <div class="text-xs text-gray-400 mt-1">sur la période</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Part clients</div>
            <div class="text-xl font-bold text-gray-900">{{ number_format($totalPartClient, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Part assurance</div>
            <div class="text-xl font-bold text-indigo-600">{{ number_format($totalPartAssurance, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>
    @endif

    {{-- ─── Recherche ───────────────────────────────────── --}}
    <form method="GET" data-no-loader>
        <input type="hidden" name="date_debut" value="{{ $dateDebut ?? $debut->format('Y-m-d') }}">
        <input type="hidden" name="date_fin"   value="{{ $dateFin ?? $fin->format('Y-m-d') }}">
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

    {{-- ─── Tableau ──────────────────────────────────────── --}}
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
                    <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800">{{ $vente->numero }}</td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-800">{{ $vente->client->full_name }}</div>
                        @if($vente->client->mutuelle)
                            <div class="text-xs text-gray-400">{{ $vente->client->mutuelle }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        @php
                            $mode = $vente->mode_paiement;
                            [$icon, $style] = match(true) {
                                $mode === 'especes'                      => ['💵', 'bg-green-50 text-green-700'],
                                $mode === 'carte'                        => ['💳', 'bg-blue-50 text-blue-700'],
                                $mode === 'virement'                     => ['🏦', 'bg-indigo-50 text-indigo-700'],
                                $mode === 'cheque'                       => ['📄', 'bg-yellow-50 text-yellow-700'],
                                str_starts_with($mode, 'mobile_wave')   => ['🔵', 'bg-cyan-50 text-cyan-700'],
                                str_starts_with($mode, 'mobile_orange') => ['🟠', 'bg-orange-50 text-orange-700'],
                                str_starts_with($mode, 'mobile_mtn')    => ['🟡', 'bg-yellow-50 text-yellow-800'],
                                default                                  => ['💰', 'bg-gray-50 text-gray-700'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $style }}">
                            {{ $icon }} {{ $vente->mode_paiement_client_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        @if($vente->mode_paiement_assurance && $vente->part_assurance > 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                🏥 {{ match($vente->mode_paiement_assurance) {
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
                    <td class="px-4 py-3 text-sm text-right hidden lg:table-cell">
                        <span class="text-gray-700">{{ number_format($vente->part_client, 0, ',', ' ') }} FCFA</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-right hidden xl:table-cell">
                        @if($vente->part_assurance > 0)
                            <span class="text-indigo-600 font-medium">{{ number_format($vente->part_assurance, 0, ',', ' ') }} FCFA</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="text-sm font-semibold text-green-700">
                            {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                        </span>
                        @if($vente->part_assurance > 0)
                            <div class="text-xs text-purple-500 mt-0.5 lg:hidden">+ Assurance</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">
                        {{ $vente->date_paiement->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        Aucune vente sur cette période
                    </td>
                </tr>
                @endforelse
            </tbody>

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
