@extends('layouts.app')
@section('title', $client->full_name)

@section('content')
<div class="space-y-5 pt-4">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="w-12 h-12 bg-[#0f2447] rounded-xl flex items-center justify-center text-white font-semibold text-lg">
                {{ substr($client->prenom, 0, 1) }}{{ substr($client->nom, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $client->full_name }}</h1>
                <p class="text-gray-500 text-sm">{{ $client->mutuelle ?? 'Pas de mutuelle' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('clients.edit', $client) }}" class="px-3 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">Modifier</a>
            <a href="{{ route('devis.create', ['client_id' => $client->id]) }}" class="px-3 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b] transition-colors">Nouveau devis</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">
        {{-- Info card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <h3 class="font-semibold text-gray-900">Informations</h3>
            @if($client->date_naissance)
                <div class="flex items-center gap-2 text-sm"><span class="text-gray-400 w-28">Date naissance</span><span>{{ $client->date_naissance->format('d/m/Y') }}</span></div>
            @endif
            @if($client->telephone)
                <div class="flex items-center gap-2 text-sm"><span class="text-gray-400 w-28">Téléphone</span><span>{{ $client->telephone }}</span></div>
            @endif
            @if($client->email)
                <div class="flex items-center gap-2 text-sm"><span class="text-gray-400 w-28">Email</span><span class="truncate">{{ $client->email }}</span></div>
            @endif
            @if($client->adresse)
                <div class="flex items-start gap-2 text-sm"><span class="text-gray-400 w-28 flex-shrink-0">Adresse</span><span>{{ $client->adresse }}</span></div>
            @endif
            @if($client->mutuelle)
                <div class="flex items-center gap-2 text-sm"><span class="text-gray-400 w-28">Mutuelle</span><span>{{ $client->mutuelle }}</span></div>
                <div class="flex items-center gap-2 text-sm"><span class="text-gray-400 w-28">N° Mutuelle</span><span>{{ $client->numero_mutuelle }}</span></div>
            @endif
            @if($client->notes)
                <div class="pt-2 border-t border-gray-100">
                    <div class="text-xs text-gray-400 mb-1">Notes</div>
                    <p class="text-sm text-gray-600">{{ $client->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Ordonnances --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Ordonnances ({{ $client->ordonnances->count() }})</h3>
                <a href="{{ route('ordonnances.create', ['client_id' => $client->id]) }}" class="text-[#1d9bf0] text-xs hover:underline">+ Ajouter</a>
            </div>
            @forelse($client->ordonnances->sortByDesc('date_ordonnance') as $ord)
                <a href="{{ route('ordonnances.show', $ord) }}" class="block p-3 bg-gray-50 rounded-lg mb-2 hover:bg-blue-50 transition-colors">
                    <div class="font-medium text-sm">Dr. {{ $ord->medecin ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-400">{{ $ord->date_ordonnance->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-500 mt-1">OD: {{ $ord->od_sphere ?? '—' }} | OG: {{ $ord->og_sphere ?? '—' }}</div>
                </a>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Aucune ordonnance</p>
            @endforelse
        </div>

        {{-- Devis & Factures --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Devis ({{ $client->devis->count() }})</h3>
                <a href="{{ route('devis.create', ['client_id' => $client->id]) }}" class="text-[#1d9bf0] text-xs hover:underline">+ Ajouter</a>
            </div>
            @forelse($client->devis->sortByDesc('created_at') as $d)
                <a href="{{ route('devis.show', $d) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2 hover:bg-blue-50 transition-colors">
                    <div>
                        <div class="font-medium text-sm">{{ $d->numero }}</div>
                        <span class="text-xs px-1.5 py-0.5 rounded {{ $d->statut_color }}">{{ $d->statut_label }}</span>
                    </div>
                    <div class="font-semibold text-sm">{{ number_format($d->montant_total, 0, ',', ' ') }} FCFA</div>
                </a>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Aucun devis</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
