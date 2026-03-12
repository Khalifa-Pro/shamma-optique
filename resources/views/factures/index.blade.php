@extends('layouts.app')
@section('title', 'Factures')

@section('content')
<div class="space-y-5 pt-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Factures</h1>
        <p class="text-gray-500 text-sm">{{ $factures->total() }} facture{{ $factures->total() > 1 ? 's' : '' }}</p>
    </div>

    <form method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
        </div>
        <select name="statut" onchange="this.form.submit()" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
            <option value="">Tous</option>
            @foreach(['en_attente' => 'En attente', 'payee' => 'Payée', 'annulee' => 'Annulée'] as $val => $label)
                <option value="{{ $val }}" {{ $statut === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">N°</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Statut</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Total</th>
                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Part client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">Échéance</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($factures as $facture)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono font-medium">{{ $facture->numero }}</td>
                        <td class="px-4 py-3 text-sm">{{ $facture->client->full_name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $facture->statut_color }}">{{ $facture->statut_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-right">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-3 text-sm text-right hidden md:table-cell">{{ number_format($facture->part_client, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden lg:table-cell">{{ $facture->date_echeance?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('factures.show', $facture) }}" class="p-1.5 text-gray-400 hover:text-[#1d9bf0] hover:bg-blue-50 rounded-lg transition-colors inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400">Aucune facture trouvée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $factures->links() }}
</div>
@endsection
