<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;


class FactureController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $statut = $request->get('statut');
        $factures = Facture::with('client')
            ->when($search, function ($q) use ($search) {
                $q->where('numero', 'like', "%$search%")
                  ->orWhereHas('client', fn($q) => $q->where('nom', 'like', "%$search%")->orWhere('prenom', 'like', "%$search%"));
            })
            ->when($statut, fn($q) => $q->where('statut', $statut))
            ->latest()->paginate(15)->withQueryString();

        return view('factures.index', compact('factures', 'search', 'statut'));
    }

    public function show(Facture $facture)
    {
        $facture->load(['client', 'devis.articles', 'vente']);
        return view('factures.show', compact('facture'));
    }

    public function payer(Request $request, Facture $facture)
    {
        $request->validate([
            'mode_paiement' => 'required|in:especes,carte,virement,cheque,mutuelle',
            'date_paiement' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $vente = Vente::create([
            'numero' => Vente::generateNumero(),
            'facture_id' => $facture->id,
            'devis_id' => $facture->devis_id,
            'client_id' => $facture->client_id,
            'montant_total' => $facture->montant_total,
            'part_client' => $facture->part_client,
            'part_assurance' => $facture->part_assurance,
            'mode_paiement' => $request->mode_paiement,
            'date_paiement' => $request->date_paiement,
            'notes' => $request->notes,
            'created_by' => session('user_id'),
        ]);

        $facture->update(['statut' => 'payee']);

        return redirect()->route('ventes.index')->with('success', 'Paiement enregistré. Vente ' . $vente->numero . ' créée.');
    }

    public function pdf(Facture $facture)
    {
        abort_if($facture->statut !== 'payee', 403, 'La facture doit être payée.');

        $facture->load(['client', 'devis.articles', 'vente']);

        $pdf = Pdf::loadView('factures.pdf', compact('facture'))
                ->setPaper('a4', 'portrait');

        return $pdf->download($facture->numero . '.pdf');
    }
}
