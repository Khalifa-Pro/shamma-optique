<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $devis->numero }} — Impression</title>
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
                <div><span class="font-semibold">DEVIS N° :</span> {{ $devis->numero }}</div>
                <div><span class="font-semibold">DATE D'ÉDITION :</span> {{ $devis->created_at->format('d/m/Y') }}</div>
                @if($devis->magasin)
                <div><span class="font-semibold">MAGASIN :</span> {{ $devis->magasin }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-center text-gray-400 italic text-sm mb-4">Une vision de lynx</div>
    <div style="height: 3px; background: linear-gradient(to right, #e74c3c, #1a5276); margin-bottom: 20px; border-radius: 2px;"></div>

    {{-- CLIENT --}}
    <div class="border border-gray-300 rounded p-4 mb-5">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <span class="font-semibold text-gray-600 text-xs uppercase">NOM &amp; PRÉNOMS :</span>
                <div class="mt-1 font-medium">{{ $devis->client->full_name }}</div>
            </div>
            <div>
                <span class="font-semibold text-gray-600 text-xs uppercase">CONTACT :</span>
                <div class="mt-1">{{ $devis->client->telephone ?? '—' }}</div>
            </div>
            <div>
                <span class="font-semibold text-gray-600 text-xs uppercase">GARANT / MUTUELLE :</span>
                <div class="mt-1">{{ $devis->client->mutuelle ?? '—' }}</div>
                @if($devis->client->numero_mutuelle)
                    <div class="text-xs text-gray-500">{{ $devis->client->numero_mutuelle }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- PRESCRIPTION --}}
    @if($devis->ordonnance)
    <div class="text-center font-bold text-sm uppercase tracking-wider mb-2 underline">Prescription Médicale</div>
    <div class="text-xs text-gray-500 text-center mb-3">
        Dr. {{ $devis->ordonnance->medecin }} — {{ $devis->ordonnance->date_ordonnance->format('d/m/Y') }}
    </div>
    <table class="w-full text-sm border-collapse mb-5">
        <thead>
            <tr>
                <th class="border border-gray-400 px-3 py-1.5 bg-gray-100 w-16"></th>
                <th class="border border-gray-400 px-3 py-1.5 bg-gray-100 text-center">SPHÈRE</th>
                <th class="border border-gray-400 px-3 py-1.5 bg-gray-100 text-center">CYLINDRIQUE</th>
                <th class="border border-gray-400 px-3 py-1.5 bg-gray-100 text-center">AXE</th>
                <th class="border border-gray-400 px-3 py-1.5 bg-gray-100 text-center">ADDITION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-gray-400 px-3 py-2 font-bold bg-gray-50 text-center">OD</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->od_sphere ?? '' }}</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->od_cylindre ?? '' }}</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->od_axe ?? '' }}</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->od_addition ?? '' }}</td>
            </tr>
            <tr>
                <td class="border border-gray-400 px-3 py-2 font-bold bg-gray-50 text-center">OG</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->og_sphere ?? '' }}</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->og_cylindre ?? '' }}</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->og_axe ?? '' }}</td>
                <td class="border border-gray-400 px-3 py-2 text-center">{{ $devis->ordonnance->og_addition ?? '' }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- TARIFICATION --}}
    <div class="text-center font-bold text-sm uppercase tracking-wider mb-3 underline">Tarification des Actes</div>

    @php
        $normaux = $devis->articles->whereIn('type', ['monture','verre_droit','verre_gauche','accessoire','autre'])->where('inclus', true);
        $options = $devis->articles->whereIn('type', ['photogray','antireflet'])->values();
    @endphp

    @if($normaux->count())
    <table class="w-full text-sm border-collapse mb-3">
        <thead>
            <tr>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-center" rowspan="2" style="width:18%">PARAMÈTRES</th>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-center" colspan="2">MARQUE / DÉSIGNATION &amp; RÉFÉRENCE</th>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-center" rowspan="2" style="width:17%">PRIX UNITAIRE</th>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-center" rowspan="2" style="width:17%">MONTANT FACTURE</th>
            </tr>
            <tr><th class="border border-gray-400" colspan="2"></th></tr>
        </thead>
        <tbody>
            @foreach($normaux as $article)
            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                <td class="border border-gray-400 px-2 py-2 font-semibold text-xs bg-gray-50 text-center">
                    {{ strtoupper($article->type_label) }}
                </td>
                <td class="border border-gray-400 px-2 py-2 text-xs" colspan="2">
                    <div class="font-medium">{{ $article->designation }}</div>
                    @if($article->marque)<div class="text-gray-500 text-xs">{{ $article->marque }}</div>@endif
                </td>
                <td class="border border-gray-400 px-2 py-2 text-right text-xs">
                    {{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA
                </td>
                <td class="border border-gray-400 px-2 py-2 text-right font-semibold text-xs">
                    {{ number_format($article->total, 0, ',', ' ') }} FCFA
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($options->count())
    <table class="w-full text-sm border-collapse mb-3">
        <thead>
            <tr>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-left" style="width:60%"></th>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-center">À COCHER</th>
                <th class="border border-gray-400 px-2 py-1.5 bg-gray-100 text-center">MONTANT FACTURE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($options as $article)
            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                <td class="border border-gray-400 px-2 py-2 font-semibold text-xs">{{ strtoupper($article->designation) }}</td>
                <td class="border border-gray-400 px-2 py-2 text-center text-lg">{{ $article->inclus ? '✓' : '☐' }}</td>
                <td class="border border-gray-400 px-2 py-2 text-right text-xs font-semibold">
                    @if($article->inclus && $article->prix_unitaire > 0)
                        {{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- TOTAL --}}
    <div class="text-center font-bold text-sm uppercase tracking-wider mb-2 underline">Total Facture</div>
    <table class="w-full text-sm border-collapse mb-6" style="width: 50%; margin-left: 50%;">
        <tbody>
            <tr>
                <td class="border border-gray-400 px-3 py-2 font-semibold bg-gray-50">MONTANT TOTAL</td>
                <td class="border border-gray-400 px-3 py-2 font-bold text-right">{{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="bg-gray-50">
                <td class="border border-gray-400 px-3 py-2 font-semibold bg-gray-50">PART {{ $devis->client->mutuelle }} / ASSURANCE</td>
                <td class="border border-gray-400 px-3 py-2 text-right">{{ $devis->part_assurance > 0 ? number_format($devis->part_assurance, 0, ',', ' ') . ' FCFA' : '—' }}</td>
            </tr>
            <tr>
                <td class="border border-gray-400 px-3 py-2 font-semibold bg-gray-50">PART ASSURÉ</td>
                <td class="border border-gray-400 px-3 py-2 text-right">{{ $devis->part_client > 0 ? number_format($devis->part_client, 0, ',', ' ') . ' FCFA' : '—' }}</td>
            </tr>
        </tbody>
    </table>

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
