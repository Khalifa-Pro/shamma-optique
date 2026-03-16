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
            <!-- Bouton de redirection direct sur whatsapp du numero de tel du client -->
            @if($client->telephone)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $client->telephone) }}?text={{ urlencode('Bonjour ' . $client->full_name . ', nous avons le plaisir de vous informer que vos lunettes sont prêtes et disponibles en magasin. Vous pouvez venir les récupérer à votre convenance. Merci de votre confiance. — Shamma Optique') }}"
                target="_blank"
                class="mt-3 inline-flex items-center gap-2 px-3 py-2 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                </a>
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
