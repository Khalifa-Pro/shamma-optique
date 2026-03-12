@extends('layouts.app')
@section('title', 'Utilisateurs')

@section('content')
<div x-data="{ addModal: false, editUser: null }">

    <div class="space-y-5 pt-4">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
                <p class="text-gray-500 text-sm">{{ $users->count() }} utilisateur{{ $users->count() > 1 ? 's' : '' }}</p>
            </div>
            <button @click="addModal = true; editUser = null"
                    class="flex items-center gap-2 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau utilisateur
            </button>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Utilisateur</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Email</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Rôle</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#0f2447]/10 rounded-full flex items-center justify-center text-xs font-semibold text-[#0f2447]">
                                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                    </div>
                                    <div class="font-medium text-sm">{{ $user->full_name }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $user->isAdmin() ? 'Admin' : 'Vendeur' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="editUser = {{ $user->toJson() }}; addModal = true"
                                            class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if($user->id !== session('user_id'))
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- Modal Ajout / Modification --}}
    <div
        x-show="addModal"
        x-cloak
        x-transition
        @click.self="addModal = false"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-xl p-6 max-w-md w-full">
            <h3 class="font-semibold text-gray-900 mb-4" x-text="editUser ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur'"></h3>

            {{-- Formulaire création --}}
            <template x-if="!editUser">
                <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" name="prenom" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" name="nom" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                            <option value="vendeur">Vendeur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="addModal = false"
                                class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                            Créer
                        </button>
                    </div>
                </form>
            </template>

            {{-- Formulaire modification --}}
            <template x-if="editUser">
                <div>
                    <form :action="`/utilisateurs/${editUser.id}`" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input type="text" name="prenom" :value="editUser.prenom" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input type="text" name="nom" :value="editUser.nom" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" :value="editUser.email" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe <span class="text-gray-400">(optionnel)</span></label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                                <select name="role" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                                    <option value="vendeur" :selected="editUser.role === 'vendeur'">Vendeur</option>
                                    <option value="admin" :selected="editUser.role === 'admin'">Administrateur</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <div class="flex items-center gap-2 mt-2">
                                    <input type="checkbox" name="actif" value="1" :checked="editUser.actif" class="rounded">
                                    <span class="text-sm">Compte actif</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="addModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b]">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </template>

        </div>
    </div>

</div>
@endsection
