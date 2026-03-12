@extends('layouts.app')
@section('title', $facture->numero)

@section('content')
<div x-data="{ payModal: false }">

    <div class="max-w-3xl mx-auto pt-4 space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('factures.index') }}" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900">{{ $facture->numero }}</h1>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $facture->statut_color }}">{{ $facture->statut_label }}</span>
                    </div>
                    <a href="{{ route('clients.show', $facture->client) }}" class="text-[#1d9bf0] text-sm hover:underline">{{ $facture->client->full_name }}</a>
                </div>
            </div>
            @if($facture->statut === 'en_attente')
                <button @click="payModal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    Enregistrer paiement
                </button>
            @endif
        </div>

        {{-- Détails facture --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="text-xs text-black mb-1"><strong style="font-size: 20px">Shamma Optique</strong></div>
                    <br>
                    <div class="text-xs text-gray-400 mb-1">Client</div>
                    <div class="font-semibold">{{ $facture->client->full_name }}</div>
                    <div class="text-sm text-gray-500">{{ $facture->client->adresse }}</div>
                    @if($facture->client->mutuelle)
                        <div class="text-sm text-gray-500">{{ $facture->client->mutuelle }} — {{ $facture->client->numero_mutuelle }}</div>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-400 mb-1">Facture</div>
                    <div class="font-semibold font-mono">{{ $facture->numero }}</div>
                    <div class="text-sm text-gray-500">Émise le {{ $facture->created_at->format('d/m/Y') }}</div>
                    @if($facture->date_echeance)
                        <div class="text-sm text-gray-500">Échéance {{ $facture->date_echeance->format('d/m/Y') }}</div>
                    @endif
                </div>
            </div>

            @if($facture->devis)
                <table class="w-full text-sm mb-6">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left pb-2 font-medium text-gray-500">Article</th>
                            <th class="text-center pb-2 font-medium text-gray-500">Qté</th>
                            <th class="text-right pb-2 font-medium text-gray-500">P.U.</th>
                            <th class="text-right pb-2 font-medium text-gray-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($facture->devis->articles as $article)
                            <tr>
                                <td class="py-2.5">{{ $article->designation }}</td>
                                <td class="py-2.5 text-center">{{ $article->quantite }}</td>
                                <td class="py-2.5 text-right">{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                <td class="py-2.5 text-right">{{ number_format($article->total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="border-t border-gray-100 pt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Part client</span>
                    <span class="font-medium">{{ number_format($facture->part_client, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Part assurance</span>
                    <span class="font-medium">{{ number_format($facture->part_assurance, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between text-base font-bold border-t border-gray-100 pt-2">
                    <span>Total TTC</span>
                    <span>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        {{-- Vente liée --}}
        @if($facture->vente)
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <div class="font-medium text-green-800">Paiement reçu</div>
                    <div class="text-sm text-green-700">
                        {{ $facture->vente->numero }} — {{ $facture->vente->date_paiement->format('d/m/Y') }} — {{ $facture->vente->mode_paiement_label }}
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Modal Paiement --}}
    <div
        x-show="payModal"
        x-cloak
        x-transition
        @click.self="payModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-xl p-6 max-w-sm w-full">
            <h3 class="font-semibold text-gray-900 mb-4">Enregistrer le paiement</h3>
            <form method="POST" action="{{ route('factures.payer', $facture) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mode de paiement</label>
                    <select name="mode_paiement" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        @foreach(['especes' => 'Espèces', 'carte' => 'Carte bancaire', 'virement' => 'Virement', 'cheque' => 'Chèque', 'mutuelle' => 'Mutuelle'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="payModal = false"
                            class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
