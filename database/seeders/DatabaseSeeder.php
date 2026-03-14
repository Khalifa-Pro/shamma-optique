<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Ordonnance;
use App\Models\Devis;
use App\Models\ArticleDevis;
use App\Models\Produit;
use App\Models\Facture;
use App\Models\Vente;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────
        // UTILISATEURS
        // ─────────────────────────────────────────────────
        $admin = User::create([
            'nom'      => 'Diallo',
            'prenom'   => 'Moussa',
            'email'    => 'admin@shamma-optique.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'actif'    => true,
        ]);

        $vendeur1 = User::create([
            'nom'      => 'Koné',
            'prenom'   => 'Aminata',
            'email'    => 'aminata@shamma-optique.com',
            'password' => Hash::make('vendeur123'),
            'role'     => 'vendeur',
            'actif'    => true,
        ]);

        $vendeur2 = User::create([
            'nom'      => 'Traoré',
            'prenom'   => 'Ibrahim',
            'email'    => 'ibrahim@shamma-optique.com',
            'password' => Hash::make('ibrahim123'),
            'role'     => 'vendeur',
            'actif'    => true,
        ]);

        // ─────────────────────────────────────────────────
        // CLIENTS
        // ─────────────────────────────────────────────────
        $c1 = Client::create([
            'nom'             => 'Coulibaly',
            'prenom'          => 'Fatoumata',
            'date_naissance'  => '1985-04-12',
            'telephone'       => '+225 07 07 11 22 33',
            'email'           => 'fatoumata.c@email.com',
            'adresse'         => 'Yopougon Ananeraie, Carrefour JEC, Abidjan',
            'mutuelle'        => 'MCI CARE',
            'numero_mutuelle' => 'MCI-2024-001',
            'notes'           => 'Cliente régulière, préfère les montures légères',
            'created_by'      => $vendeur1->id,
        ]);

        $c2 = Client::create([
            'nom'             => 'Bamba',
            'prenom'          => 'Seydou',
            'date_naissance'  => '1978-08-23',
            'telephone'       => '+225 05 56 18 31 78',
            'email'           => 'seydou.bamba@email.com',
            'adresse'         => 'Quartier Koko, Man',
            'mutuelle'        => 'MUGEFCI',
            'numero_mutuelle' => 'MUG-2024-087',
            'notes'           => '',
            'created_by'      => $vendeur1->id,
        ]);

        $c3 = Client::create([
            'nom'             => 'Ouattara',
            'prenom'          => 'Mariam',
            'date_naissance'  => '1992-01-30',
            'telephone'       => '+225 07 47 52 44 86',
            'email'           => 'mariam.o@email.com',
            'adresse'         => 'Quartier Bada, Divo',
            'mutuelle'        => 'CNPS',
            'numero_mutuelle' => 'CNPS-2024-321',
            'notes'           => 'Allergie à certaines montures métalliques',
            'created_by'      => $vendeur2->id,
        ]);

        $c4 = Client::create([
            'nom'             => 'Konaté',
            'prenom'          => 'Adama',
            'date_naissance'  => '1965-11-07',
            'telephone'       => '+225 25 23 00 15 23',
            'email'           => 'adama.konate@email.com',
            'adresse'         => 'Cocody Riviera 2, Abidjan',
            'mutuelle'        => 'MCI CARE',
            'numero_mutuelle' => 'MCI-2024-556',
            'notes'           => 'Presbyte confirmé, verres progressifs',
            'created_by'      => $vendeur1->id,
        ]);

        $c5 = Client::create([
            'nom'             => 'Yao',
            'prenom'          => 'Kouassi',
            'date_naissance'  => '2001-06-15',
            'telephone'       => '+225 07 78 40 23',
            'email'           => 'kouassi.yao@email.com',
            'adresse'         => 'Plateau, Abidjan',
            'mutuelle'        => null,
            'numero_mutuelle' => null,
            'notes'           => 'Première paire de lunettes, forte myopie',
            'created_by'      => $vendeur2->id,
        ]);

        // ─────────────────────────────────────────────────
        // ORDONNANCES
        // ─────────────────────────────────────────────────
        $o1 = Ordonnance::create([
            'client_id'       => $c1->id,
            'date_ordonnance' => '2025-10-20',
            'medecin'         => 'Dr. Kouamé',
            'od_sphere'       => '-2.00',
            'od_cylindre'     => '-0.50',
            'od_axe'          => '90',
            'od_addition'     => null,
            'og_sphere'       => '-1.75',
            'og_cylindre'     => '0',
            'og_axe'          => '0',
            'og_addition'     => null,
            'notes'           => 'Renouvellement annuel',
            'created_by'      => $vendeur1->id,
        ]);

        $o2 = Ordonnance::create([
            'client_id'       => $c2->id,
            'date_ordonnance' => '2025-12-05',
            'medecin'         => 'Dr. Diabaté',
            'od_sphere'       => '+1.25',
            'od_cylindre'     => '0',
            'od_axe'          => '0',
            'od_addition'     => null,
            'og_sphere'       => '+1.50',
            'og_cylindre'     => '-0.25',
            'og_axe'          => '120',
            'og_addition'     => null,
            'notes'           => '',
            'created_by'      => $vendeur1->id,
        ]);

        $o3 = Ordonnance::create([
            'client_id'       => $c4->id,
            'date_ordonnance' => '2026-01-10',
            'medecin'         => 'Dr. Touré',
            'od_sphere'       => '+2.00',
            'od_cylindre'     => '-0.75',
            'od_axe'          => '45',
            'od_addition'     => '+2.50',
            'og_sphere'       => '+2.25',
            'og_cylindre'     => '-0.50',
            'og_axe'          => '60',
            'og_addition'     => '+2.50',
            'notes'           => 'Verres progressifs recommandés',
            'created_by'      => $vendeur1->id,
        ]);

        $o4 = Ordonnance::create([
            'client_id'       => $c5->id,
            'date_ordonnance' => '2026-02-08',
            'medecin'         => 'Dr. Sanogo',
            'od_sphere'       => '-3.50',
            'od_cylindre'     => '0',
            'od_axe'          => '0',
            'od_addition'     => null,
            'og_sphere'       => '-3.25',
            'og_cylindre'     => '-0.25',
            'og_axe'          => '180',
            'og_addition'     => null,
            'notes'           => 'Myopie forte, indice élevé recommandé',
            'created_by'      => $vendeur2->id,
        ]);

        // ─────────────────────────────────────────────────
        // PRODUITS / STOCK
        // ─────────────────────────────────────────────────
        $p1 = Produit::create([
            'reference'     => 'MON-RB-001',
            'designation'   => 'Monture Ray-Ban RB5154',
            'marque'        => 'Ray-Ban',
            'categorie'     => 'monture_adulte',
            'prix_vente'    => 45000,
            'prix_achat'    => 22000,
            'stock_actuel'  => 8,
            'stock_minimum' => 2,
            'actif'         => true,
            'notes'         => 'Bestseller',
            'created_by'    => $admin->id,
        ]);

        $p2 = Produit::create([
            'reference'     => 'MON-SIL-001',
            'designation'   => 'Monture Silhouette Titanium',
            'marque'        => 'Silhouette',
            'categorie'     => 'monture_adulte',
            'prix_vente'    => 65000,
            'prix_achat'    => 35000,
            'stock_actuel'  => 4,
            'stock_minimum' => 2,
            'actif'         => true,
            'notes'         => 'Ultra légère',
            'created_by'    => $admin->id,
        ]);

        $p3 = Produit::create([
            'reference'     => 'VER-ESS-UNI',
            'designation'   => 'Verre Essilor Orma 1.5 Unifocal',
            'marque'        => 'Essilor',
            'categorie'     => 'verre_unifocal',
            'prix_vente'    => 25000,
            'prix_achat'    => 12000,
            'stock_actuel'  => 20,
            'stock_minimum' => 5,
            'actif'         => true,
            'notes'         => 'Verre standard antireflet inclus',
            'created_by'    => $admin->id,
        ]);

        $p4 = Produit::create([
            'reference'     => 'VER-VAR-PRO',
            'designation'   => 'Verre Varilux Comfort Max Progressif',
            'marque'        => 'Varilux',
            'categorie'     => 'verre_progressif',
            'prix_vente'    => 85000,
            'prix_achat'    => 45000,
            'stock_actuel'  => 10,
            'stock_minimum' => 3,
            'actif'         => true,
            'notes'         => 'Progressif haut de gamme',
            'created_by'    => $admin->id,
        ]);

        $p5 = Produit::create([
            'reference'     => 'VER-NIK-167',
            'designation'   => 'Verre Nikon SeeMax 1.67',
            'marque'        => 'Nikon',
            'categorie'     => 'verre_unifocal',
            'prix_vente'    => 60000,
            'prix_achat'    => 30000,
            'stock_actuel'  => 1, // ← stock faible intentionnel
            'stock_minimum' => 3,
            'actif'         => true,
            'notes'         => 'Indice élevé pour forte myopie',
            'created_by'    => $admin->id,
        ]);

        $p6 = Produit::create([
            'reference'     => 'ACC-ETU-001',
            'designation'   => 'Étui rigide + chiffon microfibre',
            'marque'        => null,
            'categorie'     => 'accessoire',
            'prix_vente'    => 3500,
            'prix_achat'    => 1200,
            'stock_actuel'  => 0, // ← rupture intentionnelle
            'stock_minimum' => 5,
            'actif'         => true,
            'notes'         => null,
            'created_by'    => $admin->id,
        ]);

        $p7 = Produit::create([
            'reference'     => 'SOL-OAK-001',
            'designation'   => 'Monture Solaire Oakley OX8046',
            'marque'        => 'Oakley',
            'categorie'     => 'monture_solaire',
            'prix_vente'    => 55000,
            'prix_achat'    => 28000,
            'stock_actuel'  => 5,
            'stock_minimum' => 2,
            'actif'         => true,
            'notes'         => null,
            'created_by'    => $admin->id,
        ]);

        // ─────────────────────────────────────────────────
        // DEVIS — statut: facture (déjà payé)
        // ─────────────────────────────────────────────────
        $d1 = Devis::create([
            'numero'         => 'DEV-2025-001',
            'client_id'      => $c1->id,
            'ordonnance_id'  => $o1->id,
            'magasin'        => 'Abidjan - Yopougon',
            'montant_total'  => 95000,
            'part_client'    => 45000,
            'part_assurance' => 50000,
            'statut'         => 'facture',
            'valide_at'      => '2025-11-10 09:00:00',
            'valide_by'      => $admin->id,
            'notes'          => 'Verres antireflet inclus',
            'created_by'     => $vendeur1->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d1->id, 'produit_id' => $p1->id, 'marque' => 'Ray-Ban',  'designation' => 'Monture Ray-Ban RB5154',    'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 45000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => $p3->id, 'marque' => 'Essilor',  'designation' => 'Verre Essilor Orma 1.5 OD', 'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => $p3->id, 'marque' => 'Essilor',  'designation' => 'Verre Essilor Orma 1.5 OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => null,    'marque' => null,        'designation' => 'Traitement Antireflet',     'type' => 'antireflet',   'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => null,    'marque' => null,        'designation' => 'Traitement Photogray',      'type' => 'photogray',    'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────────────
        // DEVIS — statut: valide (prêt à facturer)
        // ─────────────────────────────────────────────────
        $d2 = Devis::create([
            'numero'         => 'DEV-2025-002',
            'client_id'      => $c2->id,
            'ordonnance_id'  => $o2->id,
            'magasin'        => 'Man',
            'montant_total'  => 158500,
            'part_client'    => 158500,
            'part_assurance' => 0,
            'statut'         => 'valide',
            'valide_at'      => '2025-12-10 11:00:00',
            'valide_by'      => $admin->id,
            'notes'          => '',
            'created_by'     => $vendeur1->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d2->id, 'produit_id' => $p2->id, 'marque' => 'Silhouette', 'designation' => 'Monture Silhouette Titanium',    'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 65000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => $p3->id, 'marque' => 'Essilor',    'designation' => 'Verre Essilor Orma 1.5 OD',      'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => $p3->id, 'marque' => 'Essilor',    'designation' => 'Verre Essilor Orma 1.5 OG',      'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => $p6->id, 'marque' => null,         'designation' => 'Étui rigide + chiffon microfibre','type' => 'accessoire',   'quantite' => 1, 'prix_unitaire' => 3500,  'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => null,    'marque' => null,          'designation' => 'Traitement Antireflet',           'type' => 'antireflet',   'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────────────
        // DEVIS — statut: valide avec assurance
        // ─────────────────────────────────────────────────
        $d3 = Devis::create([
            'numero'         => 'DEV-2026-001',
            'client_id'      => $c4->id,
            'ordonnance_id'  => $o3->id,
            'magasin'        => 'Abidjan - Yopougon',
            'montant_total'  => 225000,
            'part_client'    => 125000,
            'part_assurance' => 100000,
            'statut'         => 'valide',
            'valide_at'      => '2026-02-10 14:30:00',
            'valide_by'      => $admin->id,
            'notes'          => 'Progressifs haut de gamme — prise en charge MCI CARE',
            'created_by'     => $vendeur1->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d3->id, 'produit_id' => $p7->id, 'marque' => 'Oakley',   'designation' => 'Monture Solaire Oakley OX8046',  'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 55000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'produit_id' => $p4->id, 'marque' => 'Varilux',  'designation' => 'Verre Varilux Comfort Max OD',   'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 85000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'produit_id' => $p4->id, 'marque' => 'Varilux',  'designation' => 'Verre Varilux Comfort Max OG',   'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 85000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'produit_id' => null,    'marque' => null,        'designation' => 'Traitement Antireflet',          'type' => 'antireflet',   'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────────────
        // DEVIS — statut: brouillon
        // ─────────────────────────────────────────────────
        $d4 = Devis::create([
            'numero'         => 'DEV-2026-002',
            'client_id'      => $c5->id,
            'ordonnance_id'  => $o4->id,
            'magasin'        => 'Abidjan - Plateau',
            'montant_total'  => 145000,
            'part_client'    => 145000,
            'part_assurance' => 0,
            'statut'         => 'brouillon',
            'valide_at'      => null,
            'valide_by'      => null,
            'notes'          => 'Indice élevé pour forte myopie',
            'created_by'     => $vendeur2->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d4->id, 'produit_id' => $p2->id, 'marque' => 'Silhouette', 'designation' => 'Monture Silhouette Titanium', 'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 65000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'produit_id' => $p5->id, 'marque' => 'Nikon',      'designation' => 'Verre Nikon SeeMax 1.67 OD', 'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 60000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'produit_id' => $p5->id, 'marque' => 'Nikon',      'designation' => 'Verre Nikon SeeMax 1.67 OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 60000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'produit_id' => null,    'marque' => null,          'designation' => 'Traitement Photogray',       'type' => 'photogray',    'quantite' => 1, 'prix_unitaire' => 15000, 'inclus' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────────────
        // DEVIS — statut: brouillon (client Ouattara)
        // ─────────────────────────────────────────────────
        $d5 = Devis::create([
            'numero'         => 'DEV-2026-003',
            'client_id'      => $c3->id,
            'ordonnance_id'  => null,
            'magasin'        => 'Divo',
            'montant_total'  => 48500,
            'part_client'    => 48500,
            'part_assurance' => 0,
            'statut'         => 'brouillon',
            'valide_at'      => null,
            'valide_by'      => null,
            'notes'          => 'Monture plastique uniquement — allergie métaux',
            'created_by'     => $vendeur2->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d5->id, 'produit_id' => null,    'marque' => 'Oxibis', 'designation' => 'Monture Oxibis plastique légère', 'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 28500, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d5->id, 'produit_id' => $p3->id, 'marque' => 'Essilor', 'designation' => 'Verre Essilor Orma 1.5 OD',     'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 10000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d5->id, 'produit_id' => $p3->id, 'marque' => 'Essilor', 'designation' => 'Verre Essilor Orma 1.5 OG',     'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 10000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────────────
        // FACTURES
        // ─────────────────────────────────────────────────

        // Facture payée — issue de d1
        $f1 = Facture::create([
            'numero'         => 'FAC-2025-001',
            'devis_id'       => $d1->id,
            'client_id'      => $c1->id,
            'montant_total'  => 95000,
            'part_client'    => 45000,
            'part_assurance' => 50000,
            'statut'         => 'payee',
            'date_echeance'  => '2025-12-15',
            'created_by'     => $vendeur1->id,
        ]);

        // Facture en attente — issue de d2
        $f2 = Facture::create([
            'numero'         => 'FAC-2025-002',
            'devis_id'       => $d2->id,
            'client_id'      => $c2->id,
            'montant_total'  => 158500,
            'part_client'    => 158500,
            'part_assurance' => 0,
            'statut'         => 'en_attente',
            'date_echeance'  => '2026-03-05',
            'created_by'     => $vendeur1->id,
        ]);

        // ─────────────────────────────────────────────────
        // VENTES
        // ─────────────────────────────────────────────────

        // Vente liée à f1 (payée)
        Vente::create([
            'numero'         => 'VTE-2025-001',
            'facture_id'     => $f1->id,
            'devis_id'       => $d1->id,
            'client_id'      => $c1->id,
            'montant_total'  => 95000,
            'part_client'    => 45000,
            'part_assurance' => 50000,
            'mode_paiement'  => 'especes',
            'date_paiement'  => '2025-11-12',
            'notes'          => 'Paiement espèces + prise en charge MCI CARE',
            'created_by'     => $vendeur1->id,
        ]);
    }
}
