<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrdonnanceController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\UserController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth.session')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/nouveau', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/modifier', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Ordonnances
    Route::get('/ordonnances', [OrdonnanceController::class, 'index'])->name('ordonnances.index');
    Route::get('/ordonnances/nouveau', [OrdonnanceController::class, 'create'])->name('ordonnances.create');
    Route::post('/ordonnances', [OrdonnanceController::class, 'store'])->name('ordonnances.store');
    Route::get('/ordonnances/{ordonnance}', [OrdonnanceController::class, 'show'])->name('ordonnances.show');
    Route::get('/ordonnances/{ordonnance}/modifier', [OrdonnanceController::class, 'edit'])->name('ordonnances.edit');
    Route::put('/ordonnances/{ordonnance}', [OrdonnanceController::class, 'update'])->name('ordonnances.update');
    Route::delete('/ordonnances/{ordonnance}', [OrdonnanceController::class, 'destroy'])->name('ordonnances.destroy');

    // Devis
    Route::get('/devis', [DevisController::class, 'index'])->name('devis.index');
    Route::get('/devis/nouveau', [DevisController::class, 'create'])->name('devis.create');
    Route::post('/devis', [DevisController::class, 'store'])->name('devis.store');
    Route::get('/devis/{devis}', [DevisController::class, 'show'])->name('devis.show');
    Route::get('/devis/{devis}/modifier', [DevisController::class, 'edit'])->name('devis.edit');
    Route::put('/devis/{devis}', [DevisController::class, 'update'])->name('devis.update');
    Route::delete('/devis/{devis}', [DevisController::class, 'destroy'])->name('devis.destroy');
    Route::post('/devis/{devis}/valider', [DevisController::class, 'valider'])->name('devis.valider');
    Route::post('/devis/{devis}/facturer', [DevisController::class, 'facturer'])->name('devis.facturer');

    // Factures
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{facture}', [FactureController::class, 'show'])->name('factures.show');
    Route::post('/factures/{facture}/payer', [FactureController::class, 'payer'])->name('factures.payer');

    // Ventes
    Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');

    // Utilisateurs (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
        Route::post('/utilisateurs', [UserController::class, 'store'])->name('users.store');
        Route::put('/utilisateurs/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
