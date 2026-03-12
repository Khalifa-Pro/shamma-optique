<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ordonnance;
use App\Models\Client;

class OrdonnanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $ordonnances = Ordonnance::with('client')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('client', function ($q) use ($search) {
                    $q->where('nom', 'like', "%$search%")
                      ->orWhere('prenom', 'like', "%$search%");
                })->orWhere('medecin', 'like', "%$search%");
            })
            ->latest()->paginate(15)->withQueryString();

        return view('ordonnances.index', compact('ordonnances', 'search'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('nom')->get();
        $selectedClientId = $request->get('client_id');
        return view('ordonnances.form', ['ordonnance' => null, 'clients' => $clients, 'selectedClientId' => $selectedClientId]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_ordonnance' => 'required|date',
            'medecin' => 'nullable|string|max:255',
            'od_sphere' => 'nullable|string|max:10',
            'od_cylindre' => 'nullable|string|max:10',
            'od_axe' => 'nullable|string|max:10',
            'od_addition' => 'nullable|string|max:10',
            'og_sphere' => 'nullable|string|max:10',
            'og_cylindre' => 'nullable|string|max:10',
            'og_axe' => 'nullable|string|max:10',
            'og_addition' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ]);
        $data['created_by'] = session('user_id');
        $ordonnance = Ordonnance::create($data);
        return redirect()->route('ordonnances.show', $ordonnance)->with('success', 'Ordonnance créée.');
    }

    public function show(Ordonnance $ordonnance)
    {
        $ordonnance->load(['client', 'devis.articles']);
        return view('ordonnances.show', compact('ordonnance'));
    }

    public function edit(Ordonnance $ordonnance)
    {
        $clients = Client::orderBy('nom')->get();
        return view('ordonnances.form', compact('ordonnance', 'clients'));
    }

    public function update(Request $request, Ordonnance $ordonnance)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_ordonnance' => 'required|date',
            'medecin' => 'nullable|string|max:255',
            'od_sphere' => 'nullable|string|max:10',
            'od_cylindre' => 'nullable|string|max:10',
            'od_axe' => 'nullable|string|max:10',
            'od_addition' => 'nullable|string|max:10',
            'og_sphere' => 'nullable|string|max:10',
            'og_cylindre' => 'nullable|string|max:10',
            'og_axe' => 'nullable|string|max:10',
            'og_addition' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ]);
        $ordonnance->update($data);
        return redirect()->route('ordonnances.show', $ordonnance)->with('success', 'Ordonnance mise à jour.');
    }

    public function destroy(Ordonnance $ordonnance)
    {
        $ordonnance->delete();
        return redirect()->route('ordonnances.index')->with('success', 'Ordonnance supprimée.');
    }
}
