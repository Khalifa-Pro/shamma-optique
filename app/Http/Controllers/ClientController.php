<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $clients = Client::when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('telephone', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('mutuelle', 'like', "%$search%");
            });
        })->latest()->paginate(15)->withQueryString();

        return view('clients.index', compact('clients', 'search'));
    }

    public function create()
    {
        return view('clients.form', ['client' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'mutuelle' => 'nullable|string|max:255',
            'numero_mutuelle' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $data['created_by'] = session('user_id');
        $client = Client::create($data);
        return redirect()->route('clients.show', $client)->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client)
    {
        $client->load(['ordonnances', 'devis.articles', 'factures', 'ventes']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.form', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'mutuelle' => 'nullable|string|max:255',
            'numero_mutuelle' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $client->update($data);
        return redirect()->route('clients.show', $client)->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }
}
