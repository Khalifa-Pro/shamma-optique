@extends('layouts.app')
@section('title', $devis ? 'Modifier devis' : 'Nouveau devis')

@section('content')
<div class="max-w-3xl mx-auto pt-4 space-y-5" x-data="devisForm()">
    <div class="flex items-center gap-3">
        <a href="{{ $devis ? route('devis.show', $devis) : route('devis.index') }}"
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">{{ $devis ? 'Modifier le devis' : 'Nouveau devis' }}</h1>
    </div>

    <form method="POST" action="{{ $devis ? route('devis.update', $devis) : route('devis.store') }}"
          class="space-y-4">
        @csrf
        @if($devis) @method('PUT') @endif

        {{-- Client & ordonnance --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Client <span class="text-red-500">*</span></label>
                <select name="client_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                    <option value="">Choisir un client</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $devis->client_id ?? $selectedClientId ?? '') == $c->id ? 'selected' : '' }}>
                            {{ $c->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Ordonnance</label>
                <select name="ordonnance_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                    <option value="">Aucune</option>
                    @foreach($ordonnances as $ord)
                        <option value="{{ $ord->id }}" {{ old('ordonnance_id', $devis->ordonnance_id ?? $selectedOrdonnanceId ?? '') == $ord->id ? 'selected' : '' }}>
                            {{ $ord->date_ordonnance->format('d/m/Y') }} — Dr. {{ $ord->medecin }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Articles --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Articles</h3>
                <button type="button" @click="addArticle()"
                        class="flex items-center gap-1.5 text-sm text-[#1d9bf0] hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Ajouter une ligne
                </button>
            </div>

            <div class="space-y-2">
                <template x-for="(article, index) in articles" :key="index">
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-4">
                            <input type="text" :name="`articles[${index}][designation]`" x-model="article.designation"
                                   placeholder="Désignation"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div class="col-span-3">
                            <select :name="`articles[${index}][type]`" x-model="article.type"
                                    class="w-full px-2 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">
                                <option value="monture">Monture</option>
                                <option value="verre_droit">Verre OD</option>
                                <option value="verre_gauche">Verre OG</option>
                                <option value="accessoire">Accessoire</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="`articles[${index}][quantite]`" x-model.number="article.quantite"
                                   min="1" placeholder="Qté"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="`articles[${index}][prix_unitaire]`" x-model.number="article.prix_unitaire"
                                   min="0" step="0.01" placeholder="Prix"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]" required>
                        </div>
                        <div class="col-span-1 flex items-center justify-between">
                            <span class="text-xs text-gray-500 font-medium" x-text="formatPrice(article.quantite * article.prix_unitaire)"></span>
                            <button type="button" @click="removeArticle(index)" x-show="articles.length > 1"
                                    class="ml-1 text-gray-300 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-xl font-bold text-gray-900" x-text="formatPrice(total)"></div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1d9bf0]">{{ old('notes', $devis->notes ?? '') }}</textarea>
        </div>

        <div class="flex gap-3">
            <a href="{{ $devis ? route('devis.show', $devis) : route('devis.index') }}"
               class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-sm text-center hover:bg-gray-50">Annuler</a>
            <button type="submit" class="flex-1 px-4 py-2.5 bg-[#0f2447] text-white rounded-lg text-sm hover:bg-[#1a3a6b] transition-colors">
                {{ $devis ? 'Enregistrer' : 'Créer le devis' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function devisForm() {
    return {
        articles: @json($articles),

        get total() {
            return this.articles.reduce((s, a) => s + (a.quantite * a.prix_unitaire), 0);
        },

        addArticle() {
            this.articles.push({
                designation: '',
                type: 'autre',
                quantite: 1,
                prix_unitaire: 0
            });
        },

        removeArticle(index) {
            this.articles.splice(index, 1);
        },

        formatPrice(v) {
            return new Intl.NumberFormat('fr-FR').format(v || 0) + ' FCFA';
        }
    }
}
</script>
@endpush
@endsection
