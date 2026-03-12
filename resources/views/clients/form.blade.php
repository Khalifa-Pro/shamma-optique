@extends('layouts.app')
@section('title', $client ? 'Modifier ' . $client->full_name : 'Nouveau client')

@section('content')
<div class="max-w-2xl mx-auto pt-4 space-y-5">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ $client ? route('clients.show', $client) : route('clients.index') }}"
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $client ? 'Modifier le client' : 'Nouveau client' }}</h1>
            @if($client)<p class="text-gray-500 text-sm">{{ $client->full_name }}</p>@endif
        </div>
    </div>

    <form method="POST" action="{{ $client ? route('clients.update', $client) : route('clients.store') }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($client) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom <span class="text-red-500">*</span></label>
                <input type="text" name="prenom" value="{{ old('prenom', $client->prenom ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                @error('prenom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom', $client->nom ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Date de naissance</label>
            <input type="date" name="date_naissance"
                value="{{ old('date_naissance', $client?->date_naissance?->toDateString()) }}"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $client->telephone ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse</label>
            <textarea name="adresse" rows="2"
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">{{ old('adresse', $client->adresse ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mutuelle</label>
                <input type="text" name="mutuelle" value="{{ old('mutuelle', $client->mutuelle ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">N° Mutuelle</label>
                <input type="text" name="numero_mutuelle" value="{{ old('numero_mutuelle', $client->numero_mutuelle ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">{{ old('notes', $client->notes ?? '') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ $client ? route('clients.show', $client) : route('clients.index') }}"
               class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm text-center hover:bg-gray-50">Annuler</a>
            <button type="submit"
                    class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b] transition-colors">
                {{ $client ? 'Enregistrer' : 'Créer le client' }}
            </button>
        </div>
    </form>
</div>
@endsection
