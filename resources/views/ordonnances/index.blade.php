@extends('layouts.app')
@section('title', 'Ordonnances')

@section('content')
<div class="space-y-5 pt-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ordonnances</h1>
            <p class="text-gray-500 text-sm">{{ $ordonnances->total() }} ordonnance{{ $ordonnances->total() > 1 ? 's' : '' }}</p>
        </div>
        <a href="{{ route('ordonnances.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouvelle ordonnance
        </a>
    </div>

    <form method="GET">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par client ou médecin..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0] bg-white">
        </div>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Client</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Médecin</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Date</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">OD sphère</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3 hidden lg:table-cell">OG sphère</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ordonnances as $ord)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm">{{ $ord->client->full_name }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">{{ $ord->medecin ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $ord->date_ordonnance->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden lg:table-cell">{{ $ord->od_sphere ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden lg:table-cell">{{ $ord->og_sphere ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('ordonnances.show', $ord) }}" class="p-1.5 text-gray-400 hover:text-[#1d9bf0] hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('ordonnances.edit', $ord) }}" class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Aucune ordonnance trouvée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $ordonnances->links() }}
</div>
@endsection
