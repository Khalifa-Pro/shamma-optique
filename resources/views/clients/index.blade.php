@extends('layouts.app')
@section('title', 'Clients')

@section('content')
<div class="space-y-5 pt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dossiers clients</h1>
            <p class="text-gray-500 text-sm">{{ $clients->total() }} client{{ $clients->total() > 1 ? 's' : '' }} enregistré{{ $clients->total() > 1 ? 's' : '' }}</p>
        </div>
        <a href="{{ route('clients.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouveau client
        </a>
    </div>

    {{-- Search --}}
    <form method="GET">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Rechercher par nom, téléphone, email..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] focus:border-transparent bg-white">
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Téléphone</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">Email</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Mutuelle</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($clients as $client)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#0f2447]/10 rounded-full flex items-center justify-center text-xs font-semibold text-[#0f2447] flex-shrink-0">
                                    {{ substr($client->prenom, 0, 1) }}{{ substr($client->nom, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-sm text-gray-900">{{ $client->full_name }}</div>
                                    @if($client->date_naissance)
                                        <div class="text-xs text-gray-400">{{ $client->date_naissance->format('d/m/Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">{{ $client->telephone ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden lg:table-cell">{{ $client->email ?: '—' }}</td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            @if($client->mutuelle)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $client->mutuelle }}</span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('clients.show', $client) }}" class="p-1.5 text-gray-400 hover:text-[#1d9bf0] hover:bg-blue-50 rounded-lg transition-colors" title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('clients.edit', $client) }}" class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($currentUser->isAdmin())
                                <button onclick="confirmDelete('{{ $client->id }}', '{{ $client->full_name }}')"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                            <svg class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Aucun client trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $clients->links() }}
</div>

{{-- Delete modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full">
        <h3 class="font-semibold text-gray-900 mb-2">Supprimer ce client ?</h3>
        <p class="text-sm text-gray-500 mb-6" id="deleteModalText"></p>
        <div class="flex gap-3">
            <button onclick="closeDelete()" class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">Annuler</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Supprimer</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteModalText').textContent = 'Êtes-vous sûr de vouloir supprimer ' + name + ' ? Cette action est irréversible.';
    document.getElementById('deleteForm').action = '/clients/' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDelete() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endpush
@endsection
