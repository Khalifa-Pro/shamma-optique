<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Ordonnance;
use App\Models\Devis;
use App\Models\ArticleDevis;
use App\Models\Facture;
use App\Models\Vente;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::create([
            'nom' => 'Martin', 'prenom' => 'Sophie',
            'email' => 'admin@optivision.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', 'actif' => true,
        ]);
        $vendeur1 = User::create([
            'nom' => 'Dupont', 'prenom' => 'Lucas',
            'email' => 'vendeur@optivision.com',
            'password' => Hash::make('vendeur123'),
            'role' => 'vendeur', 'actif' => true,
        ]);
        $vendeur2 = User::create([
            'nom' => 'Bernard', 'prenom' => 'Marie',
            'email' => 'marie@optivision.com',
            'password' => Hash::make('marie123'),
            'role' => 'vendeur', 'actif' => true,
        ]);

        // Clients
        $c1 = Client::create([
            'nom' => 'Leroy', 'prenom' => 'Émilie',
            'date_naissance' => '1985-04-12',
            'telephone' => '06 12 34 56 78',
            'email' => 'emilie.leroy@email.com',
            'adresse' => '14 rue des Lilas, 75011 Paris',
            'mutuelle' => 'MGEN', 'numero_mutuelle' => 'MGEN-2024-001',
            'notes' => 'Cliente régulière', 'created_by' => $vendeur1->id,
        ]);
        $c2 = Client::create([
            'nom' => 'Moreau', 'prenom' => 'Thomas',
            'date_naissance' => '1992-08-23',
            'telephone' => '06 98 76 54 32',
            'email' => 'thomas.moreau@email.com',
            'adresse' => '3 avenue Victor Hugo, 69002 Lyon',
            'mutuelle' => 'Harmonie Mutuelle', 'numero_mutuelle' => 'HM-2024-087',
            'notes' => '', 'created_by' => $vendeur1->id,
        ]);
        $c3 = Client::create([
            'nom' => 'Petit', 'prenom' => 'Isabelle',
            'date_naissance' => '1978-01-30',
            'telephone' => '07 11 22 33 44',
            'email' => 'isabelle.petit@email.com',
            'adresse' => '8 boulevard du Maréchal, 33000 Bordeaux',
            'mutuelle' => 'Malakoff Humanis', 'numero_mutuelle' => 'MH-2024-321',
            'notes' => 'Allergie à certaines montures métalliques', 'created_by' => $vendeur2->id,
        ]);
        $c4 = Client::create([
            'nom' => 'Durand', 'prenom' => 'Jean-Pierre',
            'date_naissance' => '1965-11-07',
            'telephone' => '06 55 44 33 22',
            'email' => 'jp.durand@email.com',
            'adresse' => '22 rue de la République, 13001 Marseille',
            'mutuelle' => 'Axa Santé', 'numero_mutuelle' => 'AXA-2024-556',
            'notes' => 'Presbyte confirmé', 'created_by' => $vendeur1->id,
        ]);
        $c5 = Client::create([
            'nom' => 'Simon', 'prenom' => 'Camille',
            'date_naissance' => '2001-06-15',
            'telephone' => '07 66 77 88 99',
            'email' => 'camille.simon@email.com',
            'adresse' => '5 place de la Fontaine, 31000 Toulouse',
            'mutuelle' => 'CPAM', 'numero_mutuelle' => 'CPAM-2024-089',
            'notes' => 'Première paire de lunettes', 'created_by' => $vendeur2->id,
        ]);

        // Ordonnances
        $o1 = Ordonnance::create([
            'client_id' => $c1->id, 'date_ordonnance' => '2025-10-20', 'medecin' => 'Dr. Fontaine',
            'od_sphere' => '-2.00', 'od_cylindre' => '-0.50', 'od_axe' => '90', 'od_addition' => '',
            'og_sphere' => '-1.75', 'og_cylindre' => '0', 'og_axe' => '0', 'og_addition' => '',
            'notes' => 'Renouvellement', 'created_by' => $vendeur1->id,
        ]);
        $o2 = Ordonnance::create([
            'client_id' => $c2->id, 'date_ordonnance' => '2025-12-05', 'medecin' => 'Dr. Lambert',
            'od_sphere' => '+1.25', 'od_cylindre' => '0', 'od_axe' => '0', 'od_addition' => '',
            'og_sphere' => '+1.50', 'og_cylindre' => '-0.25', 'og_axe' => '120', 'og_addition' => '',
            'notes' => '', 'created_by' => $vendeur1->id,
        ]);
        $o3 = Ordonnance::create([
            'client_id' => $c4->id, 'date_ordonnance' => '2026-01-10', 'medecin' => 'Dr. Rousseau',
            'od_sphere' => '+2.00', 'od_cylindre' => '-0.75', 'od_axe' => '45', 'od_addition' => '+2.50',
            'og_sphere' => '+2.25', 'og_cylindre' => '-0.50', 'og_axe' => '60', 'og_addition' => '+2.50',
            'notes' => 'Verres progressifs recommandés', 'created_by' => $vendeur1->id,
        ]);
        $o4 = Ordonnance::create([
            'client_id' => $c5->id, 'date_ordonnance' => '2026-02-08', 'medecin' => 'Dr. Girard',
            'od_sphere' => '-3.50', 'od_cylindre' => '0', 'od_axe' => '0', 'od_addition' => '',
            'og_sphere' => '-3.25', 'og_cylindre' => '-0.25', 'og_axe' => '180', 'og_addition' => '',
            'notes' => 'Myopie forte', 'created_by' => $vendeur2->id,
        ]);

        // Devis
        $d1 = Devis::create([
            'numero' => 'DEV-2025-001', 'client_id' => $c1->id, 'ordonnance_id' => $o1->id,
            'montant_total' => 315, 'statut' => 'facture',
            'notes' => 'Verres antireflet inclus', 'created_by' => $vendeur1->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d1->id, 'designation' => 'Monture Ray-Ban RB5154', 'type' => 'monture', 'quantite' => 1, 'prix_unitaire' => 145, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'designation' => 'Verre Essilor Orma 1.5 OD', 'type' => 'verre_droit', 'quantite' => 1, 'prix_unitaire' => 85, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d1->id, 'designation' => 'Verre Essilor Orma 1.5 OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 85, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $d2 = Devis::create([
            'numero' => 'DEV-2025-002', 'client_id' => $c2->id, 'ordonnance_id' => $o2->id,
            'montant_total' => 465, 'statut' => 'valide',
            'notes' => '', 'created_by' => $vendeur1->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d2->id, 'designation' => 'Monture Silhouette 2950', 'type' => 'monture', 'quantite' => 1, 'prix_unitaire' => 210, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'designation' => 'Verre Zeiss Single Vision OD', 'type' => 'verre_droit', 'quantite' => 1, 'prix_unitaire' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'designation' => 'Verre Zeiss Single Vision OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d2->id, 'designation' => 'Étui et chiffon', 'type' => 'accessoire', 'quantite' => 1, 'prix_unitaire' => 15, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $d3 = Devis::create([
            'numero' => 'DEV-2026-001', 'client_id' => $c4->id, 'ordonnance_id' => $o3->id,
            'montant_total' => 755, 'statut' => 'brouillon',
            'notes' => 'Verres progressifs haut de gamme', 'created_by' => $vendeur1->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d3->id, 'designation' => 'Monture Oakley OX8046', 'type' => 'monture', 'quantite' => 1, 'prix_unitaire' => 195, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'designation' => 'Verre Varilux Comfort Max OD', 'type' => 'verre_droit', 'quantite' => 1, 'prix_unitaire' => 280, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d3->id, 'designation' => 'Verre Varilux Comfort Max OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 280, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $d4 = Devis::create([
            'numero' => 'DEV-2026-002', 'client_id' => $c5->id, 'ordonnance_id' => $o4->id,
            'montant_total' => 565, 'statut' => 'brouillon',
            'notes' => 'Indice élevé recommandé pour forte myopie', 'created_by' => $vendeur2->id,
        ]);
        ArticleDevis::insert([
            ['devis_id' => $d4->id, 'designation' => 'Monture Titanium légère', 'type' => 'monture', 'quantite' => 1, 'prix_unitaire' => 175, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'designation' => 'Verre Nikon SeeMax 1.67 OD', 'type' => 'verre_droit', 'quantite' => 1, 'prix_unitaire' => 195, 'created_at' => now(), 'updated_at' => now()],
            ['devis_id' => $d4->id, 'designation' => 'Verre Nikon SeeMax 1.67 OG', 'type' => 'verre_gauche', 'quantite' => 1, 'prix_unitaire' => 195, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Facture
        $f1 = Facture::create([
            'numero' => 'FAC-2025-001', 'devis_id' => $d1->id, 'client_id' => $c1->id,
            'montant_total' => 315, 'part_client' => 165, 'part_assurance' => 150,
            'statut' => 'payee', 'date_echeance' => '2025-12-06', 'created_by' => $vendeur1->id,
        ]);

        // Vente
        Vente::create([
            'numero' => 'VTE-2025-001', 'facture_id' => $f1->id, 'devis_id' => $d1->id,
            'client_id' => $c1->id, 'montant_total' => 315, 'part_client' => 165, 'part_assurance' => 150,
            'mode_paiement' => 'carte', 'date_paiement' => '2025-11-12',
            'notes' => '', 'created_by' => $vendeur1->id,
        ]);
    }
}
