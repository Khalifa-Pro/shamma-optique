@extends('layouts.app')
@section('title', 'Ordonnance')

@section('content')
<div class="max-w-2xl mx-auto pt-4 space-y-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('ordonnances.index') }}" class="p-2 hover:bg-gray-100 rounded-lg text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Ordonnance du {{ $ordonnance->date_ordonnance->format('d/m/Y') }}</h1>
                <a href="{{ route('clients.show', $ordonnance->client) }}" class="text-[#1d9bf0] text-sm hover:underline">{{ $ordonnance->client->full_name }}</a>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('ordonnances.edit', $ordonnance) }}" class="px-3 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">Modifier</a>
            <a href="{{ route('devis.create', ['client_id' => $ordonnance->client_id, 'ordonnance_id' => $ordonnance->id]) }}"
               class="px-3 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">Créer devis</a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-400 mb-1">Médecin</div>
                <div class="font-medium">{{ $ordonnance->medecin ?? '—' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Date</div>
                <div class="font-medium">{{ $ordonnance->date_ordonnance->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500"></th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500">Sphère</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500">Cylindre</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500">Axe</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500">Addition</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-700 bg-gray-50">OD</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->od_sphere ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->od_cylindre ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->od_axe ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->od_addition ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-700 bg-gray-50">OG</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->og_sphere ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->og_cylindre ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->og_axe ?: '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $ordonnance->og_addition ?: '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($ordonnance->notes)
            <div>
                <div class="text-xs text-gray-400 mb-1">Notes</div>
                <p class="text-sm">{{ $ordonnance->notes }}</p>
            </div>
        @endif
    </div>

    @if($ordonnance->devis->count())
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold mb-3">Devis liés</h3>
            @foreach($ordonnance->devis as $d)
                <a href="{{ route('devis.show', $d) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2 hover:bg-blue-50 transition-colors">
                    <div>
                        <div class="font-medium text-sm">{{ $d->numero }}</div>
                        <span class="text-xs px-1.5 py-0.5 rounded {{ $d->statut_color }}">{{ $d->statut_label }}</span>
                    </div>
                    <div class="font-semibold">{{ number_format($d->montant_total, 0, ',', ' ') }} FCFA</div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
