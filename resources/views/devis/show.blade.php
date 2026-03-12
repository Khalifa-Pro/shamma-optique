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
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $devis->statut_color }}">{{ $devis->statut_label }}</span>
                    </div>
                    <a href="{{ route('clients.show', $devis->client) }}" class="text-[#1d9bf0] text-sm hover:underline">{{ $devis->client->full_name }}</a>
                </div>
            </div>
            <div class="flex gap-2">
                @if(in_array($devis->statut, ['brouillon', 'valide']))
                    <a href="{{ route('devis.edit', $devis) }}" class="px-3 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">Modifier</a>
                @endif
                @if($devis->statut === 'brouillon')
                    <form method="POST" action="{{ route('devis.valider', $devis) }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Valider</button>
                    </form>
                @endif
                @if($devis->statut === 'valide')
                    <button @click="factureModal = true" class="px-3 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">Facturer</button>
                @endif
                @if($devis->statut === 'facture' && $devis->facture)
                    <a href="{{ route('factures.show', $devis->facture) }}" class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Voir facture</a>
                @endif
            </div>
        </div>

        {{-- Articles --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Articles</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left pb-2 font-medium text-gray-500">Désignation</th>
                        <th class="text-left pb-2 font-medium text-gray-500">Type</th>
                        <th class="text-center pb-2 font-medium text-gray-500">Qté</th>
                        <th class="text-right pb-2 font-medium text-gray-500">P.U.</th>
                        <th class="text-right pb-2 font-medium text-gray-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($devis->articles as $article)
                        <tr>
                            <td class="py-2.5 font-medium">{{ $article->designation }}</td>
                            <td class="py-2.5 text-gray-500">{{ $article->type_label }}</td>
                            <td class="py-2.5 text-center">{{ $article->quantite }}</td>
                            <td class="py-2.5 text-right">{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td class="py-2.5 text-right font-medium">{{ number_format($article->total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-gray-200">
                        <td colspan="4" class="pt-3 text-right font-semibold">Total TTC</td>
                        <td class="pt-3 text-right text-lg font-bold">{{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Ordonnance --}}
        @if($devis->ordonnance)
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="font-semibold mb-2">Ordonnance liée</h3>
                <a href="{{ route('ordonnances.show', $devis->ordonnance) }}" class="text-[#1d9bf0] text-sm hover:underline">
                    {{ $devis->ordonnance->date_ordonnance->format('d/m/Y') }} — Dr. {{ $devis->ordonnance->medecin }}
                </a>
            </div>
        @endif

        {{-- Notes --}}
        @if($devis->notes)
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-xs text-gray-400 mb-1">Notes</div>
                <p class="text-sm">{{ $devis->notes }}</p>
            </div>
        @endif

    </div>

    {{-- Modal Facturer --}}
    <div
        x-show="factureModal"
        x-cloak
        x-transition
        @click.self="factureModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-xl p-6 max-w-sm w-full">
            <h3 class="font-semibold text-gray-900 mb-4">Créer une facture</h3>
            <form method="POST" action="{{ route('devis.facturer', $devis) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Montant total</label>
                    <div class="px-3 py-2 bg-gray-50 rounded-lg text-sm font-semibold">
                        {{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Part client</label>
                        <input type="number" name="part_client" step="1" min="0" value="{{ $devis->montant_total }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Part assurance</label>
                        <input type="number" name="part_assurance" step="1" min="0" value="0"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date d'échéance</label>
                    <input type="date" name="date_echeance" value="{{ now()->addDays(30)->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="factureModal = false"
                            class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
