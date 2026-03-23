<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu {{ $devis->numero }}</title>
    <style>
        @page { size: 80mm auto; margin: 0; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #1a1a1a;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm 5mm 8mm;
            line-height: 1.55;
            background: #fff;
        }

        /* ── En-tête ── */
        .header {
            text-align: center;
            border-bottom: 1px dashed #999;
            padding-bottom: 4mm;
            margin-bottom: 3.5mm;
        }
        .header img {
            max-height: 16mm;
            margin-bottom: 2mm;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .shop-name {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .shop-sub { font-size: 9px; color: #666; }

        /* ── Titre ── */
        .recu-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 2mm 0;
            margin: 3mm 0;
        }

        /* ── Sections ── */
        .section { margin-bottom: 3mm; }
        .section-title {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #888;
            border-bottom: 0.5px solid #ddd;
            padding-bottom: 1mm;
            margin-bottom: 1.5mm;
        }

        /* ── Lignes ── */
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            font-size: 10px;
        }
        .row .label { color: #444; }
        .row .value { font-weight: bold; }

        /* ── Articles ── */
        .article-row { margin-bottom: 2mm; }
        .article-name { font-weight: bold; font-size: 10px; }
        .article-detail {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #555;
            padding-left: 3mm;
        }

        /* ── Séparateur ── */
        .sep {
            border: none;
            border-top: 1px dashed #aaa;
            margin: 3mm 0;
        }

        /* ── Totaux ── */
        .total-line {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 2mm;
            margin-top: 1.5mm;
        }
        .reste-line {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: bold;
            color: #c0392b;
            margin-top: 1.5mm;
        }
        .solde-ok {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #27ae60;
            margin-top: 2mm;
        }

        /* ── Pied ── */
        .footer {
            border-top: 1px dashed #aaa;
            padding-top: 3mm;
            margin-top: 4mm;
            text-align: center;
            font-size: 9px;
            color: #666;
            line-height: 1.7;
        }
        .footer .merci {
            font-size: 11px;
            font-weight: bold;
            color: #000;
            margin-bottom: 1.5mm;
        }

        /* ── Impression ── */
        .no-print {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 8mm;
        }
        .no-print button {
            padding: 6px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }
        .btn-print { background: #1a1a1a; color: #fff; }
        .btn-close { background: #eee; color: #333; }

        @media print {
            .no-print { display: none; }
            body { padding-top: 2mm; }
        }
    </style>
</head>
<body>

{{-- Boutons hors impression --}}
<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨 Imprimer</button>
    <button class="btn-close" onclick="window.close()">✕ Fermer</button>
</div>

{{-- En-tête --}}
<div class="header">
    @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="Logo">
    @endif
    <div class="shop-name">Shamma Optique</div>
    @if($devis->magasin)
        <div class="shop-sub">{{ $devis->magasin }}</div>
    @endif
</div>

{{-- Titre --}}
<div class="recu-title">Reçu de devis</div>

{{-- Référence --}}
<div class="section">
    <div class="row">
        <span class="label">N° devis</span>
        <span class="value">{{ $devis->numero }}</span>
    </div>
    <div class="row">
        <span class="label">Date d'impression</span>
        <span class="value">{{ now()->format('d/m/Y H:i') }}</span>
    </div>
    @if($devis->valide_at)
    <div class="row">
        <span class="label">Validé le</span>
        <span class="value">{{ $devis->valide_at->format('d/m/Y') }}</span>
    </div>
    @endif
</div>

<hr class="sep">

{{-- Client --}}
<div class="section">
    <div class="section-title">Client</div>
    <div class="row">
        <span class="label">Nom</span>
        <span class="value">{{ $devis->client->full_name }}</span>
    </div>
    @if($devis->client->telephone)
    <div class="row">
        <span class="label">Téléphone</span>
        <span class="value">{{ $devis->client->telephone }}</span>
    </div>
    @endif
    @if($devis->client->mutuelle)
    <div class="row">
        <span class="label">Mutuelle</span>
        <span class="value">{{ $devis->client->mutuelle }}</span>
    </div>
    @if($devis->client->numero_mutuelle)
    <div class="row">
        <span class="label">N° mutuelle</span>
        <span class="value">{{ $devis->client->numero_mutuelle }}</span>
    </div>
    @endif
    @endif
</div>

<hr class="sep">

{{-- Articles --}}
<div class="section">
    <div class="section-title">Détail des prestations</div>
    @foreach($devis->articles->where('inclus', true) as $article)
    <div class="article-row">
        <div class="article-name">
            {{ $article->designation }}
            @if($article->marque) — {{ $article->marque }}@endif
        </div>
        <div class="article-detail">
            <span>{{ $article->quantite }} × {{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</span>
            <span>{{ number_format($article->quantite * $article->prix_unitaire, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>
    @endforeach

    {{-- Articles non inclus --}}
    @php $nonInclus = $devis->articles->where('inclus', false); @endphp
    @if($nonInclus->count())
        <div style="font-size: 8px; color: #aaa; margin-top: 2mm; border-top: 0.5px dashed #ddd; padding-top: 1.5mm;">
            Options non incluses :
            @foreach($nonInclus as $art)
                {{ $art->designation }}@if(!$loop->last),@endif
            @endforeach
        </div>
    @endif
</div>

<hr class="sep">

{{-- Totaux --}}
{{-- Totaux --}}
<div class="section totaux">
    <div class="section-title">Récapitulatif financier</div>

    <div class="row">
        <span class="label">Montant total</span>
        <span class="value">{{ number_format($devis->montant_total, 0, ',', ' ') }} FCFA</span>
    </div>

    @if($partAssurance > 0)
    <div class="row">
        <span class="label">Part {{ $devis->client->mutuelle ?? 'assurance' }}</span>
        <span class="value">{{ number_format($partAssurance, 0, ',', ' ') }} FCFA</span>
    </div>
    @endif

    <div class="row">
        <span class="label">Part assuré</span>
        <span class="value">{{ number_format($devis->part_client, 0, ',', ' ') }} FCFA</span>
    </div>

    {{-- Séparateur avant avance --}}
    <div class="row-total">
        <span>Avance versée</span>
        <span>{{ number_format($avance, 0, ',', ' ') }} FCFA</span>
    </div>

    @if($resteAPayer > 0)
    <div class="row-reste">
        <span>Reste à payer</span>
        <span>{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</span>
    </div>
    @else
    <div class="row" style="color: #27ae60; font-weight: bold; margin-top: 1mm; justify-content: center; font-size: 9px;">
        ✓ Solde intégralement réglé
    </div>
    @endif
</div>

@if($devis->ordonnance)
<hr class="sep">
<div class="section">
    <div class="section-title">Ordonnance médicale</div>
    <div class="row">
        <span class="label">Médecin</span>
        <span class="value">Dr. {{ $devis->ordonnance->medecin }}</span>
    </div>
    <div class="row">
        <span class="label">Date</span>
        <span class="value">{{ $devis->ordonnance->date_ordonnance->format('d/m/Y') }}</span>
    </div>
</div>
@endif

@if($devis->notes)
<hr class="sep">
<div class="section">
    <div class="section-title">Notes</div>
    <div style="font-size: 9px; color: #444; line-height: 1.5;">{{ $devis->notes }}</div>
</div>
@endif

{{-- Pied --}}
<hr class="sep">
<div class="footer">
    <div class="merci">Merci de votre confiance !</div>
    <div>Ce reçu ne vaut pas facture.</div>
    <div>Conservez ce document jusqu'à la livraison.</div>
    <div style="margin-top: 2mm; font-size: 8px;">
        Édité le {{ now()->format('d/m/Y à H:i') }}
    </div>
</div>

<script>
    // Auto-print si paramètre autoprint dans l'URL
    if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
        window.onload = () => setTimeout(() => window.print(), 300);
    }
</script>
</body>
</html>
