<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LogoService;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $statut = $request->get('statut');

        $factures = Facture::with('client')
            ->when($search, fn($q) => $q
                ->where('numero', 'like', "%$search%")
                ->orWhereHas('client', fn($q) => $q
                    ->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                )
            )
            ->when($statut, fn($q) => $q->where('statut', $statut))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('factures.index', compact('factures', 'search', 'statut'));
    }

    public function show(Facture $facture)
    {
        $facture->load(['client', 'devis.articles', 'vente']);
        return view('factures.show', compact('facture'));
    }

    public function payer(Request $request, Facture $facture)
    {
        $modesClient = array_keys(Vente::MODES_CLIENT);

        $rules = [
            'mode_paiement' => 'required|in:' . implode(',', $modesClient),
            'date_paiement' => 'required|date',
            'notes'         => 'nullable|string',
        ];

        if ($facture->part_assurance > 0) {
            $rules['mode_paiement_assurance'] = 'required|in:mutuelle,virement,autre';
        }

        $request->validate($rules);

        $vente = Vente::create([
            'numero'                  => Vente::generateNumero(),
            'facture_id'              => $facture->id,
            'devis_id'                => $facture->devis_id,
            'client_id'               => $facture->client_id,
            'montant_total'           => $facture->montant_total,
            'part_client'             => $facture->part_client,
            'part_assurance'          => $facture->part_assurance,
            'mode_paiement'           => $request->mode_paiement,
            'mode_paiement_assurance' => $facture->part_assurance > 0
                                            ? $request->mode_paiement_assurance
                                            : null,
            'date_paiement'           => $request->date_paiement,
            'notes'                   => $request->notes,
            'created_by'              => session('user_id'),
        ]);

        $facture->update(['statut' => 'payee']);

        return redirect()->route('ventes.index')
            ->with('success', 'Paiement enregistré. Vente ' . $vente->numero . ' créée.');
    }

    public function pdf(Request $request, Facture $facture)
    {
        abort_if($facture->statut !== 'payee', 403, 'La facture doit être payée.');

        $facture->load(['client', 'devis.articles', 'vente']);

        if ($request->get('print') == '1') {
            return view('factures.print', compact('facture'));
        }

        $logoBase64 = LogoService::base64();

        $pdf = Pdf::loadView('factures.pdf', compact('facture', 'logoBase64'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download($facture->numero . '.pdf');
    }
}
