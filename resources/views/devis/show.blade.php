@extends('layouts.app')
@section('title', $devis->numero)

@section('content')
<div x-data="{ factureModal: false }">
<div class="max-w-3xl mx-auto pt-4 space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('devis.index') }}" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-gray-900">{{ $devis->numero }}</h1>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $devis->statut_color }}">
                        {{ $devis->statut_label }}
                    </span>
                </div>
                <a href="{{ route('clients.show', $devis->client) }}" class="text-[#1d9bf0] text-sm hover:underline">
                    {{ $devis->client->full_name }}
                </a>
            </div>
        </div>

        {{-- Boutons actions --}}
        <div class="flex items-center gap-2 flex-wrap justify-end">

            {{-- Télécharger PDF devis --}}
            <a href="{{ route('devis.pdf', $devis) }}"
               class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                PDF
            </a>

            {{-- Imprimer devis --}}
            <a href="{{ route('devis.pdf', $devis) }}?print=1" target="_blank"
               class="px-3 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimer
            </a>

            {{-- Reçu — uniquement si devis validé ou facturé --}}
            @if(in_array($devis->statut, ['valide', 'facture']))

                {{-- Télécharger reçu PDF --}}
                <a href="{{ route('devis.recu', $devis) }}"
                   class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    Reçu PDF
                </a>

                {{-- Imprimer reçu --}}
                <a href="{{ route('devis.recu', $devis) }}?print=1" target="_blank"
                   class="px-3 py-2 bg-violet-600 text-white rounded-lg text-sm hover:bg-violet-700 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimer reçu
                </a>

            @endif

            {{-- Séparateur visuel --}}
            <div class="w-px h-6 bg-gray-200 mx-1"></div>

            {{-- Modifier --}}
            @if(in_array($devis->statut, ['brouillon', 'valide']))
                <a href="{{ route('devis.edit', $devis) }}"
                   class="px-3 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                    Modifier
                </a>
            @endif

            {{-- Valider --}}
            @if($devis->statut === 'brouillon')
                <form method="POST" action="{{ route('devis.valider', $devis) }}">
                    @csrf
                    <button type="submit"
                            class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Valider
                    </button>
                </form>
            @endif

            {{-- Facturer --}}
            @if($devis->statut === 'valide')
                <button @click="factureModal = true"
                        class="px-3 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                    Facturer
                </button>
            @endif

            {{-- Voir facture --}}
            @if($devis->statut === 'facture' && $devis->facture)
                <a href="{{ route('factures.show', $devis->facture) }}"
                   class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    Voir facture
                </a>
            @endif

        </div>
    </div>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Flash error --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Infos magasin + validation --}}
    @if($devis->magasin || $devis->valide_at)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="grid grid-cols-2 gap-4 text-sm">
            @if($devis->magasin)
            <div>
                <div class="text-xs text-gray-400 mb-1">Magasin</div>
                <div class="font-medium">{{ $devis->magasin }}</div>
            </div>
            @endif
            @if($devis->valide_at)
            <div>
                <div class="text-xs text-gray-400 mb-1">Validé le</div>
                <div class="font-medium">{{ $devis->valide_at->format('d/m/Y à H:i') }}</div>
                @if($devis->valideBy)
                    <div class="text-xs text-gray-400">par {{ $devis->valideBy->full_name }}</div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Prescription médicale --}}
    @if($devis->ordonnance)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-900 mb-3">Prescription médicale</h3>
        <div class="text-xs text-gray-500 mb-3">
            Dr. {{ $devis->ordonnance->medecin }} —
            <a href="{{ route('ordonnances.show', $devis->ordonnance) }}" class="text-[#1d9bf0] hover:underline">
                {{ $devis->ordonnance->date_ordonnance->format('d/m/Y') }}
            </a>
        </div>
        <table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 w-12"></th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Sphère</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Cylindrique</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Axe</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Addition</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-3 py-2 font-semibold text-xs text-gray-600 bg-gray-50">OD</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->od_sphere ?? '—' }}</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->od_cylindre ?? '—' }}</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->od_axe ?? '—' }}</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->od_addition ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 font-semibold text-xs text-gray-600 bg-gray-50">OG</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->og_sphere ?? '—' }}</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->og_cylindre ?? '—' }}</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->og_axe ?? '—' }}</td>
                    <td class="px-3 py-2 text-center">{{ $devis->ordonnance->og_addition ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Articles --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-900 mb-4">Tarification des actes</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left pb-2 font-medium text-gray-500">Désignation</th>
                    <th class="text-left pb-2 font-medium text-gray-500 hidden md:table-cell">Marque</th>
                    <th class="text-left pb-2 font-medium text-gray-500">Type</th>
                    <th class="text-center pb-2 font-medium text-gray-500">Qté</th>
                    <th class="text-right pb-2 font-medium text-gray-500">P.U.</th>
                    <th class="text-right pb-2 font-medium text-gray-500">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($devis->articles as $article)
                <tr class="{{ !$article->inclus ? 'opacity-40' : '' }}">
                    <td class="py-2.5 font-medium">
                        {{ $article->designation }}
                        @if(!$article->inclus)
                            <span class="ml-1 text-xs text-gray-400 italic">(non inclus)</span>
                        @endif
                        @if($article->produit && $article->produit->stock_alert)
                            <span class="ml-1 text-xs text-orange-500">⚠ stock faible</span>
                        @endif
                    </td>
                    <td class="py-2.5 text-gray-500 hidden md:table-cell">{{ $article->marque ?? '—' }}</td>
                    <td class="py-2.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                            {{ $article->type_label }}
                        </span>
                    </td>
                    <td class="py-2.5 text-center">{{ $article->quantite }}</td>
                    <td class="py-2.5 text-right">{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td class="py-2.5 text-right font-medium">
                        @if($article->inclus)
                            {{ number_format($article->total, 0, ',', ' ') }} FCFA
                        @else
                            <span class="text-gray-400 line-through text-xs">
                                {{ number_format($article->total, 0, ',', ' ') }} FCFA
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t border-gray-200">
                    <td colspan="5" class="pt-3 text-right font-semibold text-gray-700">Total TTC</td>
                    <td class="pt-3 text-right text-lg font-bold text-gray-900">
                        {{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Répartition si validé ou facturé --}}
    @if(in_array($devis->statut, ['valide', 'facture']) && ($devis->part_client > 0 || $devis->part_assurance > 0))
<div class="bg-white rounded-xl border border-gray-200 p-5">
    <h3 class="font-semibold text-gray-900 mb-3">Répartition du paiement</h3>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">Montant total</span>
            <span class="font-medium">{{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($devis->part_assurance > 0)
        <div class="flex justify-between">
            <span class="text-gray-500">Part {{ $devis->client->mutuelle ?? 'Assurance' }}</span>
            <span class="font-medium text-blue-600">{{ number_format($devis->part_assurance, 0, ',', ' ') }} FCFA</span>
        </div>
        @endif
        <div class="flex justify-between border-t border-gray-100 pt-2">
            <span class="text-gray-700 font-medium">Part assuré</span>
            <span class="font-bold">{{ number_format($devis->part_client, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($devis->avance > 0)
        <div class="flex justify-between">
            <span class="text-green-700 font-medium">Avance versée</span>
            <span class="font-bold text-green-700">{{ number_format($devis->avance, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="flex justify-between border-t border-gray-100 pt-2 font-bold
            {{ $devis->reste > 0 ? 'text-red-600' : 'text-green-600' }}">
            <span>{{ $devis->reste > 0 ? 'Reste à payer' : 'Soldé ✓' }}</span>
            @if($devis->reste > 0)
                <span>{{ number_format($devis->reste, 0, ',', ' ') }} FCFA</span>
            @endif
        </div>
        @endif
    </div>
</div>
@endif

    {{-- Notes --}}
    @if($devis->notes)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-400 mb-1">Notes</div>
        <p class="text-sm text-gray-700">{{ $devis->notes }}</p>
    </div>
    @endif

</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- Modal Facturer                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div x-show="factureModal"
     x-cloak
     x-transition.opacity
     @click.self="factureModal = false"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl"
         @click.stop>

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Créer une facture</h3>
            <button @click="factureModal = false" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Erreurs de stock --}}
        @if($errors->has('stock'))
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3 space-y-1"
                 x-init="factureModal = true">
                <div class="flex items-center gap-2 text-red-700 font-medium text-sm mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Stock insuffisant
                </div>
                @foreach($errors->get('stock') as $erreur)
                    <p class="text-xs text-red-600">• {{ $erreur }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST"
      action="{{ route('devis.facturer', $devis) }}"
      class="space-y-4"
      x-data="{
          total: {{ $devis->montant_total }},
          partClient: {{ old('part_client', $devis->montant_total) }},
          avance: {{ old('avance', $devis->montant_total) }},
          get partAssurance() {
              const diff = this.total - this.partClient;
              return diff >= 0 ? diff : 0;
          },
          get reste() {
              return Math.max(0, this.partClient - this.avance);
          },
          get estSolde() {
              return this.avance >= this.partClient;
          }
      }">
    @csrf

    {{-- Montant total --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Montant total</label>
        <div class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-lg text-sm font-bold text-gray-900">
            {{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA
        </div>
    </div>

    {{-- Part assuré + Part assurance --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Part assuré</label>
            <input type="number"
                   name="part_client"
                   step="1" min="0" :max="total"
                   x-model.number="partClient"
                   @input="avance = Math.min(avance, partClient)"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]"
                   required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                Part {{ $devis->client->mutuelle ?? 'Assurance' }}
            </label>
            <input type="number"
                   name="part_assurance"
                   step="1" min="0"
                   :value="partAssurance"
                   x-model.number="partAssurance"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:outline-none"
                   readonly>
        </div>
    </div>

    {{-- Barre de répartition --}}
    <div>
        <div class="text-xs text-gray-400 flex justify-between px-1 mb-1.5">
            <span>Assuré : <span class="font-medium text-gray-600" x-text="partClient.toLocaleString('fr-FR') + ' FCFA'"></span></span>
            <span>{{ $devis->client->mutuelle ?? 'Assurance' }} : <span class="font-medium text-gray-600" x-text="partAssurance.toLocaleString('fr-FR') + ' FCFA'"></span></span>
        </div>
        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-[#1d9bf0] rounded-full transition-all duration-300"
                 :style="`width: ${total > 0 ? Math.min((partClient / total) * 100, 100) : 0}%`">
            </div>
        </div>
    </div>

    {{-- ── Avance ── --}}
    <div class="border border-orange-100 bg-orange-50/40 rounded-lg p-3 space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            Avance versée
            <span class="text-gray-400 font-normal text-xs ml-1">(laisser vide = paiement total)</span>
        </label>
        <input type="number"
               name="avance"
               step="1" min="0"
               :max="partClient"
               x-model.number="avance"
               class="w-full px-3 py-2 border border-orange-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white">

        {{-- Récap avance/reste --}}
        <div class="space-y-1 pt-1">
            <div class="flex justify-between text-xs text-gray-500">
                <span>Part client total</span>
                <span class="font-medium text-gray-700"
                      x-text="partClient.toLocaleString('fr-FR') + ' FCFA'"></span>
            </div>
            <div class="flex justify-between text-xs font-semibold text-green-700">
                <span>Avance</span>
                <span x-text="avance.toLocaleString('fr-FR') + ' FCFA'"></span>
            </div>
            <div class="flex justify-between text-xs font-bold border-t border-orange-100 pt-1"
                 :class="reste > 0 ? 'text-red-600' : 'text-green-600'">
                <span x-text="reste > 0 ? 'Reste à payer' : 'Soldé ✓'"></span>
                <span x-text="reste > 0 ? reste.toLocaleString('fr-FR') + ' FCFA' : ''"></span>
            </div>
        </div>
    </div>

    {{-- Date d'échéance --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Date d'échéance</label>
        <input type="date"
               name="date_echeance"
               value="{{ old('date_echeance', now()->addDays(30)->format('Y-m-d')) }}"
               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]"
               required>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 pt-1">
        <button type="button" @click="factureModal = false"
                class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
            Annuler
        </button>
        <button type="submit"
                class="flex-1 px-4 py-2.5 text-white rounded-lg text-sm font-medium transition-colors"
                :class="estSolde ? 'bg-[#0f2447] hover:bg-[#1a3a6b]' : 'bg-orange-500 hover:bg-orange-600'">
            <span x-text="estSolde ? 'Créer la facture' : 'Facturer avec avance'"></span>
        </button>
    </div>
</form>
    </div>
</div>

</div>
@endsection
