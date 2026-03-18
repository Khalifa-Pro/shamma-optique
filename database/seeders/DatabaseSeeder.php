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
use App\Models\MouvementStock;
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
        // PRODUITS
        //
        // On crée chaque produit avec stock_actuel = stock
        // APRÈS toutes les ventes simulées ci-dessous,
        // pour que la valeur en base soit toujours juste.
        //
        // Récapitulatif des sorties simulées :
        //   p1 (Ray-Ban)      : -1  (FAC-2025-001)  → stock final : 8 - 1 = 7
        //   p2 (Silhouette)   : 0                    → stock final : 4
        //   p3 (Essilor Orma) : -2  (FAC-2025-001)  → stock final : 20 - 2 = 18
        //   p4 (Varilux)      : 0                    → stock final : 10
        //   p5 (Nikon 1.67)   : 0                    → stock final : 1  (alerte)
        //   p6 (Étui)         : 0                    → stock final : 0  (rupture)
        //   p7 (Oakley)       : 0                    → stock final : 5
        //   p8 (Acuvue)       : 0                    → stock final : 12
        //   p9 (Optifree)     : 0                    → stock final : 15
        // ─────────────────────────────────────────────────

        // stock_initial / stock_final pour tracer les mouvements proprement
        $p1 = Produit::create([
            'reference'     => 'MON-RB-001',
            'designation'   => 'Monture Ray-Ban RB5154',
            'marque'        => 'Ray-Ban',
            'categorie'     => 'monture_homme',
            'prix_vente'    => 45000,
            'prix_achat'    => 22000,
            'stock_actuel'  => 7,   // 8 initial - 1 vente
            'stock_minimum' => 2,
            'actif'         => true,
            'notes'         => 'Bestseller',
            'created_by'    => $admin->id,
        ]);

        $p2 = Produit::create([
            'reference'     => 'MON-SIL-001',
            'designation'   => 'Monture Silhouette Titanium',
            'marque'        => 'Silhouette',
            'categorie'     => 'monture_femme',
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
            'stock_actuel'  => 18,  // 20 initial - 2 ventes (OD + OG)
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
            'stock_actuel'  => 1,   // alerte : stock <= stock_minimum (3)
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
            'stock_actuel'  => 0,   // rupture
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

        $p8 = Produit::create([
            'reference'     => 'LEN-ACU-001',
            'designation'   => 'Lentilles Acuvue Oasys (boîte 6)',
            'marque'        => 'Acuvue',
            'categorie'     => 'lentille',
            'prix_vente'    => 18000,
            'prix_achat'    => 9000,
            'stock_actuel'  => 12,
            'stock_minimum' => 4,
            'actif'         => true,
            'notes'         => 'Lentilles bimensuelles',
            'created_by'    => $admin->id,
        ]);

        $p9 = Produit::create([
            'reference'     => 'ENT-OPT-001',
            'designation'   => 'Solution multifonctions Optifree 360ml',
            'marque'        => 'Optifree',
            'categorie'     => 'produit_entretien',
            'prix_vente'    => 7500,
            'prix_achat'    => 3500,
            'stock_actuel'  => 15,
            'stock_minimum' => 5,
            'actif'         => true,
            'notes'         => null,
            'created_by'    => $admin->id,
        ]);

        // ─────────────────────────────────────────────────
        // MOUVEMENTS DE STOCK — ENTRÉES INITIALES
        // stock_avant = 0, stock_apres = stock commandé
        // ─────────────────────────────────────────────────

        $entreesInitiales = [
            // [$produit, $qte_initiale_commandée]
            [$p1, 8],
            [$p2, 4],
            [$p3, 20],
            [$p4, 10],
            [$p5, 1],
            // p6 : jamais reçu de stock, pas de mouvement d'entrée
            [$p7, 5],
            [$p8, 12],
            [$p9, 15],
        ];

        foreach ($entreesInitiales as [$produit, $qte]) {
            MouvementStock::create([
                'produit_id'  => $produit->id,
                'type'        => 'entree',
                'quantite'    => $qte,
                'stock_avant' => 0,
                'stock_apres' => $qte,
                'motif'       => 'Stock initial',
                'source_type' => null,
                'source_id'   => null,
                'created_by'  => $admin->id,
                'created_at'  => '2025-10-01 08:00:00',
                'updated_at'  => '2025-10-01 08:00:00',
            ]);
        }

        // Réapprovisionnement simulé sur p5 (revenu à 1 après rupture)
        MouvementStock::create([
            'produit_id'  => $p5->id,
            'type'        => 'entree',
            'quantite'    => 3,
            'stock_avant' => 0,  // rupture précédente
            'stock_apres' => 3,
            'motif'       => 'Réapprovisionnement fournisseur',
            'source_type' => null,
            'source_id'   => null,
            'created_by'  => $admin->id,
            'created_at'  => '2025-11-15 09:00:00',
            'updated_at'  => '2025-11-15 09:00:00',
        ]);

        // Sortie suivante sur p5 : vente directe comptoir (-2)
        MouvementStock::create([
            'produit_id'  => $p5->id,
            'type'        => 'sortie',
            'quantite'    => 2,
            'stock_avant' => 3,
            'stock_apres' => 1,  // stock_actuel final = 1 (alerte)
            'motif'       => 'Vente comptoir',
            'source_type' => null,
            'source_id'   => null,
            'created_by'  => $vendeur2->id,
            'created_at'  => '2025-12-20 14:00:00',
            'updated_at'  => '2025-12-20 14:00:00',
        ]);

        // Ajustement inventaire sur p3 : perte de 2 unités détectée
        MouvementStock::create([
            'produit_id'  => $p3->id,
            'type'        => 'ajustement',
            'quantite'    => 2,
            'stock_avant' => 20,
            'stock_apres' => 18,  // avant la facture, l'inventaire a déjà corrigé
            'motif'       => 'Ajustement inventaire — verres endommagés',
            'source_type' => null,
            'source_id'   => null,
            'created_by'  => $admin->id,
            'created_at'  => '2025-10-15 10:00:00',
            'updated_at'  => '2025-10-15 10:00:00',
        ]);

        // ─────────────────────────────────────────────────
        // DEVIS
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
            ['devis_id' => $d1->id, 'produit_id' => $p1->id, 'marque' => 'Ray-Ban', 'designation' => 'Monture Ray-Ban RB5154',    'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 45000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => $p3->id, 'marque' => 'Essilor', 'designation' => 'Verre Essilor Orma 1.5 OD', 'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => $p3->id, 'marque' => 'Essilor', 'designation' => 'Verre Essilor Orma 1.5 OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => null,    'marque' => null,       'designation' => 'Traitement Antireflet',     'type' => 'antireflet',   'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'produit_id' => null,    'marque' => null,       'designation' => 'Traitement Photogray',      'type' => 'photogray',    'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

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
            ['devis_id' => $d2->id, 'produit_id' => $p2->id, 'marque' => 'Silhouette', 'designation' => 'Monture Silhouette Titanium',     'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 65000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => $p3->id, 'marque' => 'Essilor',    'designation' => 'Verre Essilor Orma 1.5 OD',       'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => $p3->id, 'marque' => 'Essilor',    'designation' => 'Verre Essilor Orma 1.5 OG',       'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 25000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => $p6->id, 'marque' => null,         'designation' => 'Étui rigide + chiffon microfibre', 'type' => 'accessoire',   'quantite' => 1, 'prix_unitaire' => 3500,  'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'produit_id' => null,    'marque' => null,          'designation' => 'Traitement Antireflet',            'type' => 'antireflet',   'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

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
            ['devis_id' => $d3->id, 'produit_id' => $p7->id, 'marque' => 'Oakley',  'designation' => 'Monture Solaire Oakley OX8046', 'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 55000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'produit_id' => $p4->id, 'marque' => 'Varilux', 'designation' => 'Verre Varilux Comfort Max OD',  'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 85000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'produit_id' => $p4->id, 'marque' => 'Varilux', 'designation' => 'Verre Varilux Comfort Max OG',  'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 85000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'produit_id' => null,    'marque' => null,       'designation' => 'Traitement Antireflet',         'type' => 'antireflet',   'quantite' => 1, 'prix_unitaire' => 0,     'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

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
            ['devis_id' => $d4->id, 'produit_id' => $p2->id, 'marque' => 'Silhouette', 'designation' => 'Monture Silhouette Titanium', 'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 65000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'produit_id' => $p5->id, 'marque' => 'Nikon',      'designation' => 'Verre Nikon SeeMax 1.67 OD', 'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 60000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'produit_id' => $p5->id, 'marque' => 'Nikon',      'designation' => 'Verre Nikon SeeMax 1.67 OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 60000, 'inclus' => true,  'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'produit_id' => null,    'marque' => null,          'designation' => 'Traitement Photogray',       'type' => 'photogray',    'quantite' => 1, 'prix_unitaire' => 15000, 'inclus' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $d5 = Devis::create([
            'numero'         => 'DEV-2026-003',
            'client_id'      => $c3->id,
            'ordonnance_id'  => null,
            'magasin'        => 'Divo',
            'montant_total'  => 48000,
            'part_client'    => 48000,
            'part_assurance' => 0,
            'statut'         => 'brouillon',
            'valide_at'      => null,
            'valide_by'      => null,
            'notes'          => 'Monture plastique uniquement — allergie métaux',
            'created_by'     => $vendeur2->id,
        ]);

        ArticleDevis::insert([
            ['devis_id' => $d5->id, 'produit_id' => null,    'marque' => 'Oxibis',  'designation' => 'Monture Oxibis plastique légère', 'type' => 'monture',      'quantite' => 1, 'prix_unitaire' => 28000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d5->id, 'produit_id' => $p3->id, 'marque' => 'Essilor', 'designation' => 'Verre Essilor Orma 1.5 OD',       'type' => 'verre_droit',  'quantite' => 1, 'prix_unitaire' => 10000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d5->id, 'produit_id' => $p3->id, 'marque' => 'Essilor', 'designation' => 'Verre Essilor Orma 1.5 OG',       'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 10000, 'inclus' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────────────
        // FACTURES
        // ─────────────────────────────────────────────────

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
        // MOUVEMENTS DE STOCK — SORTIES FACTURE FAC-2025-001
        //
        // Chronologie p3 :
        //   stock initial        = 20   (entree,     2025-10-01)
        //   ajustement inventaire = -2  (ajustement, 2025-10-15) → 18
        //   sortie FAC-2025-001  = -2   (2 verres,   2025-11-10) → 16 ?
        //
        // ATTENTION : on veut stock_actuel final = 18.
        // Donc on retire la sortie FAC de p3 : les 2 verres de d1
        // (OD + OG) partagent la même référence p3.
        // On choisit de grouper en 1 mouvement de quantite=2.
        //
        // Récap cohérent final :
        //   p3 : 20 - 2 (ajust) = 18  →  stock_actuel = 18  ✓
        //   p1 : 8 - 1 (vente)  = 7   →  stock_actuel = 7   ✓
        // ─────────────────────────────────────────────────

        // Sortie p1 — 1 monture Ray-Ban vendue avec FAC-2025-001
        MouvementStock::create([
            'produit_id'  => $p1->id,
            'type'        => 'sortie',
            'quantite'    => 1,
            'stock_avant' => 8,
            'stock_apres' => 7,
            'motif'       => 'Vente — Facture FAC-2025-001',
            'source_type' => Facture::class,
            'source_id'   => $f1->id,
            'created_by'  => $vendeur1->id,
            'created_at'  => '2025-11-10 09:30:00',
            'updated_at'  => '2025-11-10 09:30:00',
        ]);

        // Sortie p3 — 2 verres Essilor (OD + OG) vendus avec FAC-2025-001
        // stock_avant = 18 (après ajustement du 2025-10-15)
        MouvementStock::create([
            'produit_id'  => $p3->id,
            'type'        => 'sortie',
            'quantite'    => 2,
            'stock_avant' => 18,
            'stock_apres' => 16,
            'motif'       => 'Vente — Facture FAC-2025-001 (OD + OG)',
            'source_type' => Facture::class,
            'source_id'   => $f1->id,
            'created_by'  => $vendeur1->id,
            'created_at'  => '2025-11-10 09:30:00',
            'updated_at'  => '2025-11-10 09:30:00',
        ]);

        // Mise à jour du stock_actuel final de p3 après la sortie FAC-2025-001
        // 20 (initial) - 2 (ajust) - 2 (vente) = 16
        $p3->update(['stock_actuel' => 16]);

        // ─────────────────────────────────────────────────
        // VENTES
        // ─────────────────────────────────────────────────

        Vente::create([
            'numero'                  => 'VTE-2025-001',
            'facture_id'              => $f1->id,
            'devis_id'                => $d1->id,
            'client_id'               => $c1->id,
            'montant_total'           => 95000,
            'part_client'             => 45000,
            'part_assurance'          => 50000,
            'mode_paiement'           => 'especes',
            'mode_paiement_assurance' => 'mutuelle',
            'date_paiement'           => '2025-11-12',
            'notes'                   => 'Paiement espèces + prise en charge MCI CARE',
            'created_by'              => $vendeur1->id,
        ]);
    }
}
