<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Vente;
use App\Models\Produit;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCA = Vente::sum('montant_total');

        $caMonth = Vente::whereYear('date_paiement', now()->year)
            ->whereMonth('date_paiement', now()->month)
            ->sum('montant_total');

        $facturesAttente = Facture::where('statut', 'en_attente')->count();
        $devisBrouillon  = Devis::where('statut', 'brouillon')->count();
        $devisValide     = Devis::where('statut', 'valide')->count();
        $totalClients    = Client::count();

        // Stock — visible par les vendeurs
        $totalStock   = Produit::where('actif', true)->sum('stock_actuel');
        $stockAlertes = Produit::where('actif', true)
                            ->whereColumn('stock_actuel', '<=', 'stock_minimum')
                            ->count();

        // CA mensuel (6 derniers mois)
        $monthlyData = [];
        $monthNames  = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'name' => $monthNames[$date->month - 1],
                'ca'   => Vente::whereYear('date_paiement', $date->year)
                              ->whereMonth('date_paiement', $date->month)
                              ->sum('montant_total'),
            ];
        }

        $recentClients = Client::latest()->limit(5)->get();
        $recentVentes  = Vente::with('client')->latest()->limit(5)->get();
        $pendingDevis  = Devis::with('client')->where('statut', 'valide')->latest()->limit(3)->get();

        return view('dashboard.index', compact(
            'totalCA', 'caMonth',
            'facturesAttente', 'devisBrouillon', 'devisValide',
            'totalClients',
            'totalStock', 'stockAlertes',
            'monthlyData',
            'recentClients', 'recentVentes', 'pendingDevis'
        ));
    }
}
