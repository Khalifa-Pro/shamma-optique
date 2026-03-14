<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $devis->numero }} — Shamma Optique</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; background: white; }
        .page { padding: 20px 25px; }

        /* Header */
        .header { width: 100%; margin-bottom: 15px; }
        .header-table { width: 100%; }
        .header-table td { vertical-align: top; }
        .logo { height: 70px; width: auto; }
        .bandeau { background-color: #1a5276; color: white; padding: 10px 18px; border-radius: 5px; text-align: center; margin-bottom: 6px; }
        .bandeau-title { font-size: 16px; font-weight: bold; letter-spacing: 2px; margin-bottom: 3px; }
        .bandeau-sub { font-size: 9px; line-height: 1.6; }
        .doc-infos { font-size: 11px; color: #444; text-align: right; line-height: 1.8; }
        .doc-infos b { font-weight: bold; color: #222; }

        /* Slogan */
        .slogan { text-align: center; font-style: italic; color: #888; font-size: 11px; margin-bottom: 8px; }

        /* Divider */
        .divider { border: none; border-top: 3px solid #1a5276; margin-bottom: 15px; }

        /* Client */
        .client-box { border: 1px solid #ccc; border-radius: 4px; padding: 10px 12px; margin-bottom: 14px; }
        .client-table { width: 100%; }
        .client-table td { vertical-align: top; padding-right: 12px; font-size: 11px; }
        .field-label { font-size: 10px; font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 3px; }
        .field-value { font-size: 11px; font-weight: 500; }
        .field-sub { font-size: 9px; color: #888; }

        /* Section title */
        .section-title { text-align: center; font-weight: bold; font-size: 11px; text-transform: uppercase; text-decoration: underline; letter-spacing: 1px; margin-bottom: 8px; margin-top: 14px; }

        /* Médecin */
        .medecin { text-align: center; font-size: 10px; color: #666; margin-bottom: 6px; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 10px; }
        th { background-color: #eef2f5; border: 1px solid #aaa; padding: 6px 7px; font-weight: bold; text-align: center; font-size: 10px; }
        td { border: 1px solid #aaa; padding: 5px 7px; vertical-align: middle; }
        .td-grey { background-color: #f5f7f9; font-weight: bold; text-align: center; }
        .td-right { text-align: right; }
        .td-center { text-align: center; }
        .td-left { text-align: left; }
        .row-alt { background-color: #f9fafb; }

        /* Total */
        .total-wrapper { text-align: right; }
        .total-table { width: 50%; margin-left: 50%; border-collapse: collapse; font-size: 11px; }
        .total-table td { border: 1px solid #aaa; padding: 6px 10px; }
        .total-table .td-label { background-color: #f5f7f9; font-weight: bold; }
        .total-table .td-val { text-align: right; }

        /* Signature */
        .signature { text-align: right; font-weight: bold; font-size: 11px; text-decoration: underline; text-transform: uppercase; letter-spacing: 1px; margin: 24px 0 12px 0; }

        /* Footer */
        .footer { border-top: 2px solid #1a5276; padding-top: 8px; margin-top: 20px; text-align: center; font-size: 9px; color: #555; line-height: 1.8; }
        .footer b { color: #333; }
        .footer .email { color: #2563eb; }
        .footer .rccm { color: #aaa; }

        /* Options table */
        .options-table th { text-align: left; padding-left: 10px; }
    </style>
</head>
<body>
<div class="page">

    {{-- EN-TÊTE --}}
    <table class="header-table">
        <tr>
            <td style="width: 35%;">
                <img src="{{ public_path('asset/img/SHAMMA_OPTIQUE_LOGO.png') }}"
                     class="logo" alt="Shamma Optique">
            </td>
            <td style="width: 65%; text-align: right;">
                <div class="bandeau">
                    <div class="bandeau-title">SHAMMA OPTIQUE</div>
                    <div class="bandeau-sub">Optique – Optométrie – Contactologie</div>
                    <div class="bandeau-sub">Verres optiques, Monture verres solaires</div>
                    <div class="bandeau-sub">Service après vente</div>
                </div>
                <div class="doc-infos">
                    <div><b>DEVIS N° :</b> {{ $devis->numero }}</div>
                    <div><b>DATE D'ÉDITION :</b> {{ $devis->created_at->format('d/m/Y') }}</div>
                    @if($devis->magasin)
                    <div><b>MAGASIN :</b> {{ $devis->magasin }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="slogan">Une vision de lynx</div>
    <hr class="divider">

    {{-- CLIENT --}}
    <div class="client-box">
        <table class="client-table">
            <tr>
                <td style="width: 33%;">
                    <div class="field-label">NOM &amp; PRÉNOMS :</div>
                    <div class="field-value">{{ $devis->client->full_name }}</div>
                </td>
                <td style="width: 33%;">
                    <div class="field-label">CONTACT :</div>
                    <div class="field-value">{{ $devis->client->telephone ?? '—' }}</div>
                </td>
                <td style="width: 34%;">
                    <div class="field-label">GARANT / MUTUELLE :</div>
                    <div class="field-value">{{ $devis->client->mutuelle ?? '—' }}</div>
                    @if($devis->client->numero_mutuelle)
                        <div class="field-sub">{{ $devis->client->numero_mutuelle }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- PRESCRIPTION --}}
    @if($devis->ordonnance)
    <div class="section-title">Prescription Médicale</div>
    <div class="medecin">
        Dr. {{ $devis->ordonnance->medecin }} — {{ $devis->ordonnance->date_ordonnance->format('d/m/Y') }}
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;"></th>
                <th>SPHÈRE</th>
                <th>CYLINDRIQUE</th>
                <th>AXE</th>
                <th>ADDITION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="td-grey">OD</td>
                <td class="td-center">{{ $devis->ordonnance->od_sphere ?? '' }}</td>
                <td class="td-center">{{ $devis->ordonnance->od_cylindre ?? '' }}</td>
                <td class="td-center">{{ $devis->ordonnance->od_axe ?? '' }}</td>
                <td class="td-center">{{ $devis->ordonnance->od_addition ?? '' }}</td>
            </tr>
            <tr class="row-alt">
                <td class="td-grey">OG</td>
                <td class="td-center">{{ $devis->ordonnance->og_sphere ?? '' }}</td>
                <td class="td-center">{{ $devis->ordonnance->og_cylindre ?? '' }}</td>
                <td class="td-center">{{ $devis->ordonnance->og_axe ?? '' }}</td>
                <td class="td-center">{{ $devis->ordonnance->og_addition ?? '' }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- TARIFICATION --}}
    <div class="section-title">Tarification des Actes</div>

    @php
        $normaux = $devis->articles->whereIn('type', ['monture','verre_droit','verre_gauche','accessoire','autre'])->where('inclus', true);
        $options = $devis->articles->whereIn('type', ['photogray','antireflet'])->values();
    @endphp

    @if($normaux->count())
    <table>
        <thead>
            <tr>
                <th style="width: 18%;" rowspan="2">PARAMÈTRES</th>
                <th colspan="2">MARQUE / DÉSIGNATION &amp; RÉFÉRENCE</th>
                <th style="width: 17%;" rowspan="2">PRIX UNITAIRE</th>
                <th style="width: 17%;" rowspan="2">MONTANT FACTURE</th>
            </tr>
            <tr>
                <th colspan="2" style="font-size: 9px; color: #555; font-weight: normal;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($normaux as $article)
            <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                <td class="td-grey">{{ strtoupper($article->type_label) }}</td>
                <td colspan="2" class="td-left">
                    <b>{{ $article->designation }}</b>
                    @if($article->marque)
                        <br><span style="color:#666; font-size:9px;">{{ $article->marque }}</span>
                    @endif
                </td>
                <td class="td-right">{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                <td class="td-right"><b>{{ number_format($article->total, 0, ',', ' ') }} FCFA</b></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($options->count())
    <table class="options-table">
        <thead>
            <tr>
                <th style="width: 60%; text-align: left; padding-left: 10px;"></th>
                <th style="width: 20%;">À COCHER</th>
                <th style="width: 20%;">MONTANT FACTURE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($options as $article)
            <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                <td class="td-left"><b>{{ strtoupper($article->designation) }}</b></td>
                <td class="td-center" style="font-size: 14px;">{{ $article->inclus ? '&#10003;' : '&#9634;' }}</td>
                <td class="td-right">
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
    <div class="section-title">Total Facture</div>
    <table class="total-table">
        <tbody>
            <tr>
                <td class="td-label">MONTANT TOTAL</td>
                <td class="td-val"><b>{{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA</b></td>
            </tr>
            <tr class="row-alt">
                <td class="td-label">PART MCI CARE / ASSURANCE</td>
                <td class="td-val">
                    {{ $devis->part_assurance > 0 ? number_format($devis->part_assurance, 0, ',', ' ') . ' FCFA' : '—' }}
                </td>
            </tr>
            <tr>
                <td class="td-label">PART ASSURÉ</td>
                <td class="td-val">
                    {{ $devis->part_client > 0 ? number_format($devis->part_client, 0, ',', ' ') . ' FCFA' : '—' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature">Cachet &amp; Signature</div>

    {{-- FOOTER --}}
    <div class="footer">
        <p><b>Abidjan</b> : Yopougon Ananeraie, Carrefour JEC Promo. Cel : (+225) 07 07 78 40 23 / 05 56 18 31 78</p>
        <p><b>Man</b> : Quartier koko non loin de l'école koko. Tel : 07 07 78 40 23</p>
        <p><b>Divo</b> : Quartier bada face à la pharmacie amitié. Tel : 07 59 66 28 47</p>
        <p>Cel : (+225) 07 47 52 44 86, Tél : (+225) 25 23 00 15 23, Email : <span class="email">shammaoptique9@gmail.com</span></p>
        <p class="rccm">RCCM N° CI-ABJ-2015 A-16105 N° CC 1533222P</p>
    </div>

</div>
</body>
</html>
