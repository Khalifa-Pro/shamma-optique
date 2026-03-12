<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ordonnance extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'date_ordonnance', 'medecin',
        'od_sphere', 'od_cylindre', 'od_axe', 'od_addition',
        'og_sphere', 'og_cylindre', 'og_axe', 'og_addition',
        'notes', 'created_by',
    ];

    protected $casts = [
        'date_ordonnance' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }
}
