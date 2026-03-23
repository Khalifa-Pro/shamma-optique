<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 9px;
        color: #1a1a1a;
        width: 80mm;
        padding: 4mm 5mm;
        line-height: 1.5;
    }

    /* ── En-tête ── */
    .header {
        text-align: center;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 4mm;
        margin-bottom: 3mm;
    }
    .header img {
        max-height: 14mm;
        margin-bottom: 2mm;
    }
    .header .shop-name {
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .header .shop-sub {
        font-size: 8px;
        color: #666;
    }

    /* ── Titre reçu ── */
    .recu-title {
        text-align: center;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 3mm 0;
        padding: 2mm 0;
        border-top: 1.5px solid #1a1a1a;
        border-bottom: 1.5px solid #1a1a1a;
    }

    /* ── Bloc client ── */
    .section {
        margin-bottom: 3mm;
    }
    .section-title {
        font-size: 7px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        margin-bottom: 1.5mm;
        border-bottom: 0.5px solid #eee;
        padding-bottom: 0.5mm;
    }
    .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8mm;
        font-size: 8.5px;
    }
    .row .label { color: #555; }
    .row .value { font-weight: bold; text-align: right; }

    /* ── Articles ── */
    .articles {
        margin-bottom: 3mm;
    }
    .article-row {
        margin-bottom: 1mm;
        font-size: 8px;
    }
    .article-name {
        font-weight: bold;
        color: #222;
    }
    .article-detail {
        display: flex;
        justify-content: space-between;
        color: #555;
        font-size: 7.5px;
        padding-left: 2mm;
    }

    /* ── Séparateur ── */
    .sep {
        border: none;
        border-top: 1px dashed #ccc;
        margin: 3mm 0;
    }

    /* ── Totaux ── */
    .totaux .row { font-size: 8.5px; }
    .totaux .row-total {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        font-weight: bold;
        border-top: 1.5px solid #1a1a1a;
        padding-top: 2mm;
        margin-top: 1mm;
    }
    .totaux .row-reste {
        display: flex;
        justify-content: space-between;
        font-size: 9px;
        font-weight: bold;
        color: #c0392b;
        margin-top: 1mm;
    }

    /* ── Pied ── */
    .footer {
        margin-top: 4mm;
        border-top: 1px dashed #ccc;
        padding-top: 3mm;
        text-align: center;
        font-size: 7.5px;
        color: #777;
        line-height: 1.6;
    }
    .footer .merci {
        font-size: 9px;
        font-weight: bold;
        color: #1a1a1a;
        margin-bottom: 1mm;
    }
</style>
</head>
<body>

{{-- En-tête --}}
<div class="header">
    @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="Logo">
    @endif
    <div class="shop-name">Shamma Optique</div>
    <div class="shop-sub">
        @if($devis->magasin){{ $devis->magasin }}@endif
    </div>
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
        <span class="label">Date</span>
        <span class="value">{{ now()->format('d/m/Y à H:i') }}</span>
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
        <span class="label">Tél.</span>
        <span class="value">{{ $devis->client->telephone }}</span>
    </div>
    @endif
    @if($devis->client->mutuelle)
    <div class="row">
        <span class="label">Mutuelle</span>
        <span class="value">{{ $devis->client->mutuelle }}</span>
    </div>
    @endif
</div>

<hr class="sep">

{{-- Articles inclus --}}
<div class="section">
    <div class="section-title">Détail des prestations</div>
    <div class="articles">
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
    </div>
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
    <div class="section-title">Ordonnance</div>
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
    <div style="font-size: 8px; color: #444; line-height: 1.5;">{{ $devis->notes }}</div>
</div>
@endif

{{-- Pied de page --}}
<hr class="sep">
<div class="footer">
    <div class="merci">Merci de votre confiance !</div>
    <div>Ce reçu ne vaut pas facture.</div>
    <div>Conservez ce document jusqu'à la livraison.</div>
    <div style="margin-top: 2mm; font-size: 7px;">
        Imprimé le {{ now()->format('d/m/Y à H:i') }}
    </div>
</div>

</body>
</html>
