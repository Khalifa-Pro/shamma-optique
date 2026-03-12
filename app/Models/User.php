<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'created_by');
    }
}
