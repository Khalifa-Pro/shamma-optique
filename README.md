# OptiVision — Plateforme de Gestion Optique (Laravel)

Application de gestion complète pour un opticien, convertie de React/TypeScript vers **Laravel 11** + Blade + Alpine.js.

---

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js (optionnel, pour les assets)

### Étapes

```bash
# 1. Cloner / extraire le projet
cd optivision

# 2. Installer les dépendances PHP
composer install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
# SQLite (par défaut, aucune config nécessaire) :
touch database/database.sqlite

# MySQL (optionnel) :
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=optivision
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrer et peupler la base de données
php artisan migrate --seed

# 6. Lancer le serveur de développement
php artisan serve
```

Accéder à : **http://localhost:8000**

---

## 👤 Comptes de démonstration

| Email | Mot de passe | Rôle |
|-------|-------------|------|
| admin@optivision.com | admin123 | Administrateur |
| vendeur@optivision.com | vendeur123 | Vendeur |
| marie@optivision.com | marie123 | Vendeur |

---

## 🏗️ Architecture

### Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ClientController.php
│   │   ├── OrdonnanceController.php
│   │   ├── DevisController.php
│   │   ├── FactureController.php
│   │   ├── VenteController.php
│   │   └── UserController.php
│   └── Middleware/
│       ├── AuthSession.php       # Auth via session (sans Sanctum)
│       └── AdminMiddleware.php   # Réservé aux admins
├── Models/
│   ├── User.php
│   ├── Client.php
│   ├── Ordonnance.php
│   ├── Devis.php
│   ├── ArticleDevis.php
│   ├── Facture.php
│   └── Vente.php
database/
├── migrations/                   # 6 migrations
└── seeders/DatabaseSeeder.php    # Données de démonstration
resources/views/
├── layouts/app.blade.php         # Layout principal avec sidebar
├── auth/login.blade.php
├── dashboard/index.blade.php
├── clients/
├── ordonnances/
├── devis/
├── factures/
├── ventes/
└── utilisateurs/
routes/web.php                    # Toutes les routes
```

### Stack technique
- **Backend** : Laravel 11 (PHP 8.2+)
- **Frontend** : Blade + Tailwind CSS (CDN) + Alpine.js
- **Base de données** : SQLite (développement) / MySQL (production)
- **Authentification** : Session PHP simple (sans package externe)

---

## 📋 Fonctionnalités

### Gestion des clients
- Liste avec recherche et pagination
- Fiche détaillée (ordonnances, devis, factures)
- Création / modification / suppression

### Ordonnances
- Grille de prescription (OD/OG : sphère, cylindre, axe, addition)
- Liaison client et médecin

### Devis
- Articles dynamiques (monture, verres, accessoires)
- Statuts : Brouillon → Validé → Facturé / Annulé
- Conversion en facture avec répartition client/assurance

### Factures
- Génération depuis devis validé
- Enregistrement du paiement → création d'une vente

### Ventes
- Suivi des encaissements
- Modes : espèces, carte, virement, chèque, mutuelle
- Statistiques CA total / mensuel

### Utilisateurs (admin)
- Gestion des rôles (admin / vendeur)
- Activation / désactivation de comptes

---

## 🔢 Numérotation automatique

| Type | Format |
|------|--------|
| Devis | `DEV-2026-001` |
| Facture | `FAC-2026-001` |
| Vente | `VTE-2026-001` |

---

## 🗄️ Modèle de données

```
users
  └── clients (created_by)
        ├── ordonnances
        └── devis
              ├── articles_devis
              └── factures
                    └── ventes
```
