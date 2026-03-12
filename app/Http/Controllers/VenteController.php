<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;

class VenteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $ventes = Vente::with('client')
            ->when($search, function ($q) use ($search) {
                $q->where('numero', 'like', "%$search%")
                  ->orWhereHas('client', fn($q) => $q->where('nom', 'like', "%$search%")->orWhere('prenom', 'like', "%$search%"));
            })
            ->latest()->paginate(15)->withQueryString();

        $totalCA = Vente::sum('montant_total');
        $caMonth = Vente::whereYear('date_paiement', now()->year)
            ->whereMonth('date_paiement', now()->month)
            ->sum('montant_total');

        return view('ventes.index', compact('ventes', 'search', 'totalCA', 'caMonth'));
    }
}
