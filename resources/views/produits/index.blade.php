@extends('layouts.app')
@section('title', 'Stock')

@section('content')
<div x-data="{ addModal: false, editProduit: null }" class="space-y-5 pt-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stock</h1>
            <p class="text-gray-500 text-sm">{{ $produits->total() }} produit{{ $produits->total() > 1 ? 's' : '' }}</p>
        </div>
        <button @click="addModal = true; editProduit = null"
                class="flex items-center gap-2 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau produit
        </button>
    </div>

    {{-- Alertes stock --}}
    @if($alertes->count())
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ea580c" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            <span class="text-sm font-semibold text-orange-800">{{ $alertes->count() }} produit(s) en stock faible ou en rupture</span>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($alertes as $alerte)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium {{ $alerte->stock_color }}">
                    {{ $alerte->designation }}
                    <span class="font-bold">({{ $alerte->stock_actuel }})</span>
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Rechercher..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
        </div>
        <select name="categorie" onchange="this.form.submit()"
                class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
            <option value="">Toutes catégories</option>
            @foreach(['monture_adulte' => 'Monture adulte', 'monture_enfant' => 'Monture enfant', 'monture_solaire' => 'Monture solaire', 'verre_unifocal' => 'Verre unifocal', 'verre_progressif' => 'Verre progressif', 'lentille' => 'Lentille', 'produit_entretien' => 'Produit entretien', 'accessoire' => 'Accessoire'] as $val => $label)
                <option value="{{ $val }}" {{ ($categorie ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Produit</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Catégorie</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Marque</th>
                    <th class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Stock</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Prix vente</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($produits as $produit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm">{{ $produit->designation }}</div>
                            @if($produit->reference)
                                <div class="text-xs text-gray-400 font-mono">{{ $produit->reference }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $produit->categorie_label }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $produit->marque ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $produit->stock_color }}">
                                {{ $produit->stock_actuel }}
                                @if($produit->stock_actuel === 0)
                                    <span>· Rupture</span>
                                @elseif($produit->stock_alert)
                                    <span>· Faible</span>
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-right">
                            {{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="editProduit = {{ $produit->toJson() }}; addModal = true"
                                        class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <a href="{{ route('produits.show', $produit) }}"
                                   class="p-1.5 text-gray-400 hover:text-[#1d9bf0] hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">Aucun produit trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $produits->links() }}

    {{-- Modal Ajout / Modification --}}
    <div x-show="addModal" x-cloak x-transition @click.self="addModal = false"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <h3 class="font-semibold text-gray-900 mb-4" x-text="editProduit ? 'Modifier le produit' : 'Nouveau produit'"></h3>

            <template x-if="!editProduit">
                <form method="POST" action="{{ route('produits.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                            <input type="text" name="reference" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marque</label>
                            <input type="text" name="marque" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Désignation *</label>
                        <input type="text" name="designation" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                        <select name="categorie" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                            @foreach(['monture_adulte' => 'Monture adulte', 'monture_enfant' => 'Monture enfant', 'monture_solaire' => 'Monture solaire', 'verre_unifocal' => 'Verre unifocal', 'verre_progressif' => 'Verre progressif', 'verre_degressif' => 'Verre dégressif', 'lentille' => 'Lentille', 'produit_entretien' => 'Produit entretien', 'accessoire' => 'Accessoire', 'autre' => 'Autre'] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix vente (FCFA) *</label>
                            <input type="number" name="prix_vente" step="1" min="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix achat (FCFA)</label>
                            <input type="number" name="prix_achat" step="1" min="0" value="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock initial</label>
                            <input type="number" name="stock_actuel" step="1" min="0" value="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Seuil alerte</label>
                            <input type="number" name="stock_minimum" step="1" min="0" value="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="addModal = false"
                                class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">Annuler</button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">Créer</button>
                    </div>
                </form>
            </template>

            <template x-if="editProduit">
                <div>
                    <form :action="`/produits/${editProduit.id}`" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                                <input type="text" name="reference" :value="editProduit.reference" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Marque</label>
                                <input type="text" name="marque" :value="editProduit.marque" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Désignation *</label>
                            <input type="text" name="designation" :value="editProduit.designation" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prix vente (FCFA) *</label>
                                <input type="number" name="prix_vente" step="1" min="0" :value="editProduit.prix_vente" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock actuel</label>
                                <input type="number" name="stock_actuel" step="1" min="0" :value="editProduit.stock_actuel" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Seuil alerte</label>
                            <input type="number" name="stock_minimum" step="1" min="0" :value="editProduit.stock_minimum" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="addModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">Annuler</button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection
