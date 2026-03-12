<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'telephone',
        'email', 'adresse', 'mutuelle', 'numero_mutuelle',
        'notes', 'created_by',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
