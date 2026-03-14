@extends('layouts.app')
@section('title', $produit->designation)

@section('content')
<div x-data="{ entreeModal: false }" class="max-w-3xl mx-auto pt-4 space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('produits.index') }}" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-gray-900">{{ $produit->designation }}</h1>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $produit->stock_color }}">
                        {{ $produit->stock_label }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">{{ $produit->categorie_label }}{{ $produit->marque ? ' · ' . $produit->marque : '' }}</p>
            </div>
        </div>
        <button @click="entreeModal = true"
                class="flex items-center gap-2 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Entrée stock
        </button>
    </div>

    {{-- Carte infos --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold {{ $produit->stock_actuel === 0 ? 'text-red-600' : ($produit->stock_alert ? 'text-orange-600' : 'text-gray-900') }}">
                {{ $produit->stock_actuel }}
            </div>
            <div class="text-xs text-gray-500 mt-1">Stock actuel</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $produit->stock_minimum }}</div>
            <div class="text-xs text-gray-500 mt-1">Seuil alerte</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ number_format($produit->prix_vente, 0, ',', ' ') }}</div>
            <div class="text-xs text-gray-500 mt-1">Prix vente (FCFA)</div>
        </div>
    </div>

    {{-- Historique mouvements --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Historique des mouvements</h3>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Date</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Type</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Motif</th>
                    <th class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Qté</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Stock après</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($mouvements as $mvt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $mvt->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if($mvt->type === 'entree')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    ↑ Entrée
                                </span>
                            @elseif($mvt->type === 'sortie')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    ↓ Sortie
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    ⇄ Ajustement
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $mvt->motif ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-center {{ $mvt->type === 'entree' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $mvt->type === 'entree' ? '+' : '-' }}{{ $mvt->quantite }}
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-right">{{ $mvt->stock_apres }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400">Aucun mouvement enregistré</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($mouvements->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $mouvements->links() }}</div>
        @endif
    </div>

    {{-- Modal entrée stock --}}
    <div x-show="entreeModal" x-cloak x-transition @click.self="entreeModal = false"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full">
            <h3 class="font-semibold text-gray-900 mb-4">Entrée de stock</h3>
            <form method="POST" action="{{ route('produits.entree', $produit) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Quantité à ajouter *</label>
                    <input type="number" name="quantite" min="1" step="1"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Motif</label>
                    <input type="text" name="motif" value="Réapprovisionnement" placeholder="Ex: Réapprovisionnement, Retour client..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="entreeModal = false"
                            class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
