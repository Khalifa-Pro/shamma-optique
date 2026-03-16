<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $facture->numero }} — Shamma Optique</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; background: white; }
        .page { padding: 20px 25px; }

        /* Header */
        .header-table { width: 100%; margin-bottom: 15px; }
        .header-table td { vertical-align: top; }
        .logo { height: 70px; width: auto; }
        .bandeau { background-color: #1a5276; color: white; padding: 10px 18px; border-radius: 5px; text-align: center; margin-bottom: 6px; }
        .bandeau-title { font-size: 16px; font-weight: bold; letter-spacing: 2px; margin-bottom: 3px; }
        .bandeau-sub { font-size: 9px; line-height: 1.6; }
        .doc-infos { font-size: 11px; color: #444; text-align: right; line-height: 1.8; }
        .doc-infos b { font-weight: bold; color: #222; }

        /* Slogan + Divider */
        .slogan { text-align: center; font-style: italic; color: #888; font-size: 11px; margin-bottom: 8px; }
        .divider { border: none; border-top: 3px solid #1a5276; margin-bottom: 15px; }

        /* Grid 2 colonnes */
        .grid-table { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-bottom: 14px; }
        .grid-table td { vertical-align: top; border: 1px solid #ccc; border-radius: 4px; padding: 10px 12px; width: 50%; font-size: 11px; }
        .col-label { font-size: 10px; font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .client-name { font-weight: bold; font-size: 13px; margin-bottom: 3px; }
        .client-sub { font-size: 10px; color: #555; line-height: 1.6; }
        .badge-paye { display: inline-block; background-color: #dcfce7; color: #166534; font-weight: bold; font-size: 10px; padding: 3px 10px; border-radius: 20px; margin-top: 6px; }

        /* Pay info table */
        .pay-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .pay-table td { border: none; padding: 2px 0; }
        .pay-label { color: #666; width: 45%; }
        .pay-val { font-weight: bold; text-align: right; }

        /* Section title */
        .section-title { text-align: center; font-weight: bold; font-size: 11px; text-transform: uppercase; text-decoration: underline; letter-spacing: 1px; margin-bottom: 8px; margin-top: 14px; }

        /* Ordonnance info */
        .ord-box { background-color: #f5f7f9; border: 1px solid #ddd; border-radius: 4px; padding: 6px 10px; font-size: 10px; margin-bottom: 8px; }

        /* Tables */
        table.data-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 10px; }
        table.data-table th { border: 1px solid #aaa; padding: 6px 7px; font-weight: bold; text-align: center; }
        table.data-table th.th-blue { background-color: #1a5276; color: white; }
        table.data-table td { border: 1px solid #aaa; padding: 5px 7px; vertical-align: middle; }
        .td-right { text-align: right; }
        .td-center { text-align: center; }
        .td-left { text-align: left; }
        .td-grey { background-color: #f5f7f9; font-weight: bold; }
        .row-alt { background-color: #f9fafb; }

        /* Total recap */
        .total-wrapper { text-align: right; margin-bottom: 20px; }
        .total-table { width: 55%; margin-left: 45%; border-collapse: collapse; font-size: 11px; }
        .total-table td { border: 1px solid #aaa; padding: 6px 10px; }
        .total-label { background-color: #f5f7f9; font-weight: bold; }
        .total-val { text-align: right; }
        .total-blue td { background-color: #1a5276; color: white; font-weight: bold; font-size: 12px; }
        .total-blue .total-val { text-align: right; }

        /* Signature */
        .signature { text-align: right; font-weight: bold; font-size: 11px; text-decoration: underline; text-transform: uppercase; letter-spacing: 1px; margin: 24px 0 12px 0; }

        /* Footer */
        .footer { border-top: 2px solid #1a5276; padding-top: 8px; margin-top: 20px; text-align: center; font-size: 9px; color: #555; line-height: 1.8; }
        .footer b { color: #333; }
        .footer .email { color: #2563eb; }
        .footer .rccm { color: #aaa; }
    </style>
</head>
<body>
<div class="page">

    {{-- EN-TÊTE --}}
    <table class="header-table">
        <tr>
            <td style="width: 35%;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo" alt="Shamma Optique">
                @else
                    <div style="font-size: 18px; font-weight: bold; color: #1a5276; padding: 10px 0;">SHAMMA OPTIQUE</div>
                @endif
            </td>
            <td style="width: 65%; text-align: right;">
                <div class="bandeau">
                    <div class="bandeau-title">SHAMMA OPTIQUE</div>
                    <div class="bandeau-sub">Optique – Optométrie – Contactologie</div>
                    <div class="bandeau-sub">Verres optiques, Monture verres solaires</div>
                    <div class="bandeau-sub">Service après vente</div>
                </div>
                <div class="doc-infos">
                    <div><b>FACTURE N° :</b> {{ $facture->numero }}</div>
                    <div><b>DATE :</b> {{ $facture->created_at->format('d/m/Y') }}</div>
                    @if($facture->date_echeance)
                    <div><b>ÉCHÉANCE :</b> {{ $facture->date_echeance->format('d/m/Y') }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="slogan">Une vision de lynx</div>
    <hr class="divider">

    {{-- CLIENT + PAIEMENT --}}
    <table class="grid-table">
        <tr>
            {{-- Client --}}
            <td>
                <div class="col-label">Client</div>
                <div class="client-name">{{ $facture->client->full_name }}</div>
                @if($facture->client->adresse)
                    <div class="client-sub">{{ $facture->client->adresse }}</div>
                @endif
                @if($facture->client->telephone)
                    <div class="client-sub">{{ $facture->client->telephone }}</div>
                @endif
                @if($facture->client->mutuelle)
                    <div class="client-sub" style="margin-top: 4px;">
                        <b>Mutuelle :</b> {{ $facture->client->mutuelle }}
                        @if($facture->client->numero_mutuelle) — {{ $facture->client->numero_mutuelle }} @endif
                    </div>
                @endif
            </td>

            {{-- Paiement --}}
            <td>
                <div class="col-label">Paiement</div>
                @if($facture->vente)
                <table class="pay-table">
                    <tr>
                        <td class="pay-label">Mode :</td>
                        <td class="pay-val">{{ $facture->vente->mode_paiement_label }}</td>
                    </tr>
                    <tr>
                        <td class="pay-label">Date :</td>
                        <td class="pay-val">{{ $facture->vente->date_paiement->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="pay-label">Réf. vente :</td>
                        <td class="pay-val" style="font-size: 9px; font-family: monospace;">{{ $facture->vente->numero }}</td>
                    </tr>
                </table>
                @endif
                <div class="badge-paye">&#10003; PAYÉE</div>
            </td>
        </tr>
    </table>

    {{-- ARTICLES --}}
    @if($facture->devis && $facture->devis->articles->count())
    <div class="section-title">Détail des Prestations</div>

    @if($facture->devis->ordonnance)
    <div class="ord-box">
        <b>Ordonnance :</b> Dr. {{ $facture->devis->ordonnance->medecin }}
        du {{ $facture->devis->ordonnance->date_ordonnance->format('d/m/Y') }}
        @if($facture->devis->magasin) — <b>Magasin :</b> {{ $facture->devis->magasin }} @endif
    </div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th class="th-blue td-left" style="padding-left: 10px; width: 40%;">Désignation</th>
                <th class="th-blue" style="width: 15%;">Type</th>
                <th class="th-blue" style="width: 8%;">Qté</th>
                <th class="th-blue" style="width: 18%;">P.U.</th>
                <th class="th-blue" style="width: 19%;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->devis->articles->where('inclus', true) as $article)
            <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                <td class="td-left">
                    <b>{{ $article->designation }}</b>
                    @if($article->marque)
                        <br><span style="color: #666; font-size: 9px;">{{ $article->marque }}</span>
                    @endif
                </td>
                <td class="td-center">{{ $article->type_label }}</td>
                <td class="td-center">{{ $article->quantite }}</td>
                <td class="td-right">{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                <td class="td-right"><b>{{ number_format($article->total, 0, ',', ' ') }} FCFA</b></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- TOTAL --}}
    <table class="total-table">
        <tbody>
            <tr>
                <td class="total-label">Part assuré</td>
                <td class="total-val">{{ number_format($facture->part_client, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="row-alt">
                <td class="total-label">Part MCI CARE / Assurance</td>
                <td class="total-val">{{ number_format($facture->part_assurance, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="total-blue">
                <td style="padding: 8px 10px;">TOTAL TTC</td>
                <td style="padding: 8px 10px; text-align: right;">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
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
