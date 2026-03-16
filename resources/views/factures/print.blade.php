<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $facture->numero }} — Impression</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', Arial, sans-serif; background: white; }
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            @page { margin: 1cm; size: A4; }
        }
    </style>
</head>
<body class="bg-white text-gray-900">

{{-- Boutons --}}
<div class="no-print fixed top-4 right-4 flex gap-2 z-50">
    <button onclick="window.print()"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg text-sm shadow-lg hover:bg-gray-800">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Imprimer
    </button>
    <button onclick="window.close()"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm shadow-lg hover:bg-gray-200">
        Fermer
    </button>
</div>

{{-- PAGE A4 --}}
<div class="max-w-[210mm] mx-auto min-h-[297mm] p-8">

    {{-- EN-TÊTE --}}
    <div class="flex items-start justify-between mb-6">
        <img src="{{ asset('asset/img/SHAMMA_OPTIQUE_LOGO.png') }}"
             alt="Shamma Optique" style="height: 80px; width: auto; object-fit: contain;">
        <div class="text-right">
            <div style="background-color: #1a5276; color: white; padding: 10px 20px; border-radius: 6px; margin-bottom: 8px; text-align: center;">
                <div style="font-size: 20px; font-weight: 700; letter-spacing: 2px; margin-bottom: 4px;">SHAMMA OPTIQUE</div>
                <div style="font-size: 11px; font-weight: 500; margin-bottom: 2px;">Optique – Optométrie – Contactologie</div>
                <div style="font-size: 10px; opacity: 0.9;">Verres optiques, Monture verres solaires</div>
                <div style="font-size: 10px; opacity: 0.9;">Service après vente</div>
            </div>
            <div class="text-sm text-gray-600 space-y-0.5">
                <div><span class="font-semibold">FACTURE N° :</span> <span class="font-mono">{{ $facture->numero }}</span></div>
                <div><span class="font-semibold">DATE :</span> {{ $facture->created_at->format('d/m/Y') }}</div>
                @if($facture->date_echeance)
                <div><span class="font-semibold">ÉCHÉANCE :</span> {{ $facture->date_echeance->format('d/m/Y') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-center text-gray-400 italic text-sm mb-4">Une vision de lynx</div>
    <div style="height: 3px; background: linear-gradient(to right, #e74c3c, #1a5276); margin-bottom: 20px; border-radius: 2px;"></div>

    {{-- CLIENT + PAIEMENT --}}
    <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="border border-gray-300 rounded p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase mb-2">Client</div>
            <div class="font-bold text-base">{{ $facture->client->full_name }}</div>
            @if($facture->client->adresse)
                <div class="text-sm text-gray-600 mt-1">{{ $facture->client->adresse }}</div>
            @endif
            @if($facture->client->telephone)
                <div class="text-sm text-gray-600">{{ $facture->client->telephone }}</div>
            @endif
            @if($facture->client->mutuelle)
                <div class="mt-2 text-xs">
                    <span class="font-semibold">Mutuelle :</span> {{ $facture->client->mutuelle }}
                    @if($facture->client->numero_mutuelle) — {{ $facture->client->numero_mutuelle }} @endif
                </div>
            @endif
        </div>
        <div class="border border-gray-300 rounded p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase mb-2">Paiement</div>
            @if($facture->vente)
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Mode :</span>
                    <span class="font-medium">{{ $facture->vente->mode_paiement_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date :</span>
                    <span class="font-medium">{{ $facture->vente->date_paiement->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Réf. vente :</span>
                    <span class="font-mono text-xs">{{ $facture->vente->numero }}</span>
                </div>
            </div>
            @endif
            <div class="mt-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                    ✓ PAYÉE
                </span>
            </div>
        </div>
    </div>

    {{-- ARTICLES --}}
    @if($facture->devis && $facture->devis->articles->count())
    <div class="text-center font-bold text-sm uppercase tracking-wider mb-2 underline">
        Détail des Prestations
    </div>

    @if($facture->devis->ordonnance)
    <div class="mb-3 p-3 bg-gray-50 rounded border border-gray-200 text-xs">
        <span class="font-semibold">Ordonnance :</span>
        Dr. {{ $facture->devis->ordonnance->medecin }}
        du {{ $facture->devis->ordonnance->date_ordonnance->format('d/m/Y') }}
        @if($facture->devis->magasin)
            — <span class="font-semibold">Magasin :</span> {{ $facture->devis->magasin }}
        @endif
    </div>
    @endif

    <table class="w-full text-sm border-collapse mb-5">
        <thead>
            <tr style="background-color: #1a5276; color: white;">
                <th class="px-3 py-2 text-left border border-gray-400" style="width: 40%;">Désignation</th>
                <th class="px-3 py-2 text-center border border-gray-400" style="width: 15%;">Type</th>
                <th class="px-3 py-2 text-center border border-gray-400" style="width: 8%;">Qté</th>
                <th class="px-3 py-2 text-right border border-gray-400" style="width: 18%;">P.U.</th>
                <th class="px-3 py-2 text-right border border-gray-400" style="width: 19%;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->devis->articles->where('inclus', true) as $article)
            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                <td class="border border-gray-300 px-3 py-2">
                    <div class="font-medium">{{ $article->designation }}</div>
                    @if($article->marque)
                        <div class="text-xs text-gray-500">{{ $article->marque }}</div>
                    @endif
                </td>
                <td class="border border-gray-300 px-3 py-2 text-center text-xs">{{ $article->type_label }}</td>
                <td class="border border-gray-300 px-3 py-2 text-center">{{ $article->quantite }}</td>
                <td class="border border-gray-300 px-3 py-2 text-right">{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                <td class="border border-gray-300 px-3 py-2 text-right font-semibold">{{ number_format($article->total, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- TOTAL --}}
    <div class="flex justify-end mb-6">
        <table class="text-sm border-collapse" style="min-width: 280px;">
            <tbody>
                <tr class="bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Part assuré</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">{{ number_format($facture->part_client, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Part MCI CARE / Assurance</td>
                    <td class="border border-gray-300 px-4 py-2 text-right">{{ number_format($facture->part_assurance, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr style="background-color: #1a5276; color: white;">
                    <td class="border border-gray-400 px-4 py-3 font-bold text-base">TOTAL TTC</td>
                    <td class="border border-gray-400 px-4 py-3 text-right font-bold text-base">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- SIGNATURE --}}
    <div class="flex justify-end mb-8">
        <div class="font-bold text-sm uppercase tracking-wider underline">Cachet &amp; Signature</div>
    </div>

    {{-- FOOTER --}}
    <div style="border-top: 2px solid #1a5276; padding-top: 10px; margin-top: 20px;">
        <div class="text-center text-xs text-gray-600 space-y-1">
            <p><span class="font-semibold">Abidjan</span> : Yopougon Ananeraie, Carrefour JEC Promo. Cel : (+225) 07 07 78 40 23 / 05 56 18 31 78</p>
            <p><span class="font-semibold">Man</span> : Quartier koko non loin de l'école koko. Tel : 07 07 78 40 23</p>
            <p><span class="font-semibold">Divo</span> : Quartier bada face à la pharmacie amitié. Tel : 07 59 66 28 47</p>
            <p>Cel : (+225) 07 47 52 44 86, Tél : (+225) 25 23 00 15 23, Email : <span class="text-blue-600">shammaoptique9@gmail.com</span></p>
            <p class="text-gray-400">RCCM N° CI-ABJ-2015 A-16105 N° CC 1533222P</p>
        </div>
    </div>

</div>

<script>
    window.addEventListener('load', () => setTimeout(() => window.print(), 500));
</script>
</body>
</html>
