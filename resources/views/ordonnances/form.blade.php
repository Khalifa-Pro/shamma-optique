@extends('layouts.app')
@section('title', $ordonnance ? 'Modifier ordonnance' : 'Nouvelle ordonnance')

@section('content')
<div class="max-w-2xl mx-auto pt-4 space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ $ordonnance ? route('ordonnances.show', $ordonnance) : route('ordonnances.index') }}"
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">{{ $ordonnance ? 'Modifier l\'ordonnance' : 'Nouvelle ordonnance' }}</h1>
    </div>

    <form method="POST" action="{{ $ordonnance ? route('ordonnances.update', $ordonnance) : route('ordonnances.store') }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($ordonnance) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Client <span class="text-red-500">*</span></label>
                <select name="client_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    <option value="">Choisir un client</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $ordonnance->client_id ?? $selectedClientId ?? '') == $c->id ? 'selected' : '' }}>
                            {{ $c->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Date ordonnance <span class="text-red-500">*</span></label>
                <input type="date" name="date_ordonnance"
                    value="{{ old('date_ordonnance', $ordonnance?->date_ordonnance?->toDateString()) }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Médecin prescripteur</label>
            <input type="text" name="medecin" value="{{ old('medecin', $ordonnance->medecin ?? '') }}"
                   placeholder="Dr. Nom"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
        </div>

        {{-- Prescription table --}}
        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-3">Prescription</h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500"></th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Sphère</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Cylindre</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Axe</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Addition</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach([['OD', 'od'], ['OG', 'og']] as [$label, $prefix])
                            <tr>
                                <td class="px-3 py-2 font-medium text-gray-700 bg-gray-50">{{ $label }}</td>
                                @foreach(['sphere', 'cylindre', 'axe', 'addition'] as $field)
                                    <td class="px-2 py-1.5">
                                        <input type="text" name="{{ $prefix }}_{{ $field }}"
                                               value="{{ old($prefix.'_'.$field, $ordonnance->{$prefix.'_'.$field} ?? '') }}"
                                               placeholder="—"
                                               class="w-full text-center px-2 py-1 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#1d9bf0]">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">{{ old('notes', $ordonnance->notes ?? '') }}</textarea>
        </div>

        <div class="flex gap-3">
            <a href="{{ $ordonnance ? route('ordonnances.show', $ordonnance) : route('ordonnances.index') }}"
               class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm text-center hover:bg-gray-50">Annuler</a>
            <button type="submit" class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b] transition-colors">
                {{ $ordonnance ? 'Enregistrer' : 'Créer l\'ordonnance' }}
            </button>
        </div>
    </form>
</div>
@endsection
