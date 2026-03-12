<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Vente;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCA = Vente::sum('montant_total');
        $caMonth = Vente::whereYear('date_paiement', now()->year)
            ->whereMonth('date_paiement', now()->month)
            ->sum('montant_total');
        $facturesAttente = Facture::where('statut', 'en_attente')->count();
        $devisBrouillon = Devis::where('statut', 'brouillon')->count();
        $devisValide = Devis::where('statut', 'valide')->count();
        $totalClients = Client::count();

        // Monthly data (last 6 months)
        $monthlyData = [];
        $monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $ca = Vente::whereYear('date_paiement', $date->year)
                ->whereMonth('date_paiement', $date->month)
                ->sum('montant_total');
            $monthlyData[] = ['name' => $monthNames[$date->month - 1], 'ca' => $ca];
        }

        $recentClients = Client::latest()->limit(5)->get();
        $recentVentes = Vente::with('client')->latest()->limit(5)->get();
        $pendingDevis = Devis::with('client')->where('statut', 'valide')->limit(3)->get();

        return view('dashboard.index', compact(
            'totalCA', 'caMonth', 'facturesAttente', 'devisBrouillon',
            'devisValide', 'totalClients', 'monthlyData', 'recentClients',
            'recentVentes', 'pendingDevis'
        ));
    }
}
