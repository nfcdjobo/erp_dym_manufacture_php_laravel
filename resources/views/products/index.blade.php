{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Produits - ERP DYM Manufacture')
@section('page-subtitle', 'Gestion des Produits')

@section('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .floating-label {
        transform: translateY(0.5rem);
        transition: all 0.3s ease;
    }

    .form-input:focus + .floating-label,
    .form-input:not(:placeholder-shown) + .floating-label {
        transform: translateY(-1.5rem) scale(0.85);
    }

    .drag-over {
        border-color: #3b82f6 !important;
        background-color: #dbeafe !important;
    }
</style>
@endsection

@section('body-class', 'bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen')

@section('content')
<div x-data="productsManager()">
    <!-- En-tête avec statistiques -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1 min-w-0 mb-6 lg:mb-0">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Gestion des Produits</h2>
                <p class="mt-2 text-gray-600">Gérez votre catalogue de produits avec des filtres avancés</p>

                <!-- Statistiques rapides -->
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="glass-effect rounded-xl p-4 shadow-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $products->total() }}</div>
                        <div class="text-sm text-gray-600">Produits trouvés</div>
                    </div>
                    <div class="glass-effect rounded-xl p-4 shadow-lg">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $products->where('is_active', true)->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Produits actifs</div>
                    </div>
                    <div class="glass-effect rounded-xl p-4 shadow-lg">
                        <div class="text-2xl font-bold text-orange-600">
                            {{ $products->where('stock_quantity', '<', 10)->count() }}
                        </div>
                        <div class="text-sm text-gray-600">Stock faible</div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button @click="toggleFilters()"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    <span x-text="showFilters ? 'Masquer filtres' : 'Filtres avancés'"></span>
                </button>
                <button @click="openCreateModal()"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau Produit
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mb-8">
        <div class="glass-effect rounded-2xl p-6 shadow-xl">
            <form method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Recherche textuelle -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche globale</label>
                        <input type="text" name="search" id="search" value="{{ $search }}"
                               placeholder="Rechercher par nom, description ou catégorie..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="category_filter" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <select name="category_filter" id="category_filter"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryFilter === $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status_filter" id="status_filter"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ $statusFilter === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ $statusFilter === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Prix -->
                    <div>
                        <label for="price_min" class="block text-sm font-medium text-gray-700 mb-2">Prix minimum</label>
                        <input type="number" name="price_min" id="price_min" step="0.01" min="0"
                               value="{{ $priceMin }}"
                               placeholder="0.00"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="price_max" class="block text-sm font-medium text-gray-700 mb-2">Prix maximum</label>
                        <input type="number" name="price_max" id="price_max" step="0.01" min="0"
                               value="{{ $priceMax }}"
                               placeholder="999.99"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock_min" class="block text-sm font-medium text-gray-700 mb-2">Stock minimum</label>
                        <input type="number" name="stock_min" id="stock_min" min="0"
                               value="{{ $stockMin }}"
                               placeholder="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="stock_max" class="block text-sm font-medium text-gray-700 mb-2">Stock maximum</label>
                        <input type="number" name="stock_max" id="stock_max" min="0"
                               value="{{ $stockMax }}"
                               placeholder="999"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Dates -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date de création - Du</label>
                        <input type="date" name="date_from" id="date_from"
                               value="{{ $dateFrom }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Au</label>
                        <input type="date" name="date_to" id="date_to"
                               value="{{ $dateTo }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-3">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des produits -->
    <div class="glass-effect rounded-2xl overflow-hidden shadow-xl">
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50 backdrop-blur">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => ($sort === 'name' && $order === 'asc') ? 'desc' : 'asc']) }}"
                               class="group inline-flex items-center hover:text-gray-900">
                                Produit
                                @if($sort === 'name')
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($order === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'category_name', 'order' => ($sort === 'category_name' && $order === 'asc') ? 'desc' : 'asc']) }}"
                               class="group inline-flex items-center hover:text-gray-900">
                                Catégorie
                                @if($sort === 'category_name')
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($order === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'order' => ($sort === 'price' && $order === 'asc') ? 'desc' : 'asc']) }}"
                               class="group inline-flex items-center hover:text-gray-900">
                                Prix
                                @if($sort === 'price')
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($order === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock_quantity', 'order' => ($sort === 'stock_quantity' && $order === 'asc') ? 'desc' : 'asc']) }}"
                               class="group inline-flex items-center hover:text-gray-900">
                                Stock
                                @if($sort === 'stock_quantity')
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($order === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => ($sort === 'created_at' && $order === 'asc') ? 'desc' : 'asc']) }}"
                               class="group inline-flex items-center hover:text-gray-900">
                                Date
                                @if($sort === 'created_at')
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($order === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white/30 divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="hover:bg-white/50 animate-fade-in transition-all duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                @if($product->main_image)
                                <div class="flex-shrink-0 w-16 h-16">
                                    <img src="{{ Storage::url($product->main_image['path']) }}"
                                         alt="{{ $product->name }}"
                                         class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                </div>
                                @else
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $product->name }}
                                    </div>
                                    <div class="text-sm text-gray-600 max-w-xs truncate">
                                        {{ $product->description ?? 'Aucune description' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200">
                                {{ $product->category->name ?? 'Sans catégorie' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">
                                {{ $product->formatted_price }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->stock_quantity > 10 ? 'bg-green-100 text-green-800 border border-green-200' : ($product->stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-red-100 text-red-800 border border-red-200') }}">
                                {{ $product->stock_quantity }} unité{{ $product->stock_quantity > 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                {{ $product->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button @click="openShowModal('{{ $product->id }}')"
                                        class="text-blue-600 hover:text-blue-900 transition duration-200 p-2 rounded-lg hover:bg-blue-50" title="Voir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button @click="openEditModal('{{ $product->id }}')"
                                        class="text-indigo-600 hover:text-indigo-900 transition duration-200 p-2 rounded-lg hover:bg-indigo-50" title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="openDeleteModal('{{ $product->id }}', '{{ addslashes($product->name) }}')"
                                        class="text-red-600 hover:text-red-900 transition duration-200 p-2 rounded-lg hover:bg-red-50" title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination améliorée -->
        @if($products->hasPages())
        <div class="bg-white/50 px-6 py-4 border-t border-gray-200">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun produit trouvé</h3>
            <p class="mt-2 text-gray-500">
                @if(!empty($search) || !empty($categoryFilter) || !empty($statusFilter) || !empty($priceMin) || !empty($priceMax) || !empty($stockMin) || !empty($stockMax) || !empty($dateFrom) || !empty($dateTo))
                    Aucun résultat ne correspond à vos critères de recherche.
                @else
                    Commencez par créer votre premier produit.
                @endif
            </p>
            <div class="mt-6 flex justify-center space-x-3">
                @if(!empty($search) || !empty($categoryFilter) || !empty($statusFilter) || !empty($priceMin) || !empty($priceMax) || !empty($stockMin) || !empty($stockMax) || !empty($dateFrom) || !empty($dateTo))
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Réinitialiser filtres
                </a>
                @else
                <button @click="openCreateModal()"
                        class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau Produit
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>

    @include('products.modals')
</div>
@endsection

@section('scripts')
<script>
    function productsManager() {
        return {
            showFormModal: false,
            showViewModal: false,
            showDeleteModal: false,
            showFilters: {{ request()->hasAny(['search', 'category_filter', 'status_filter', 'price_min', 'price_max', 'stock_min', 'stock_max', 'date_from', 'date_to']) ? 'true' : 'false' }},
            isEditing: false,
            loading: false,
            categories: @json($categories),
            selectedImages: [],
            imagesToRemove: [],
            formData: {
                id: '',
                name: '',
                description: '',
                price: '',
                stock_quantity: 0,
                category_id: '',
                is_active: true,
                images: null
            },
            viewData: null,
            deleteItem: {
                id: '',
                name: ''
            },
            notification: {
                show: false,
                type: '',
                message: ''
            },

            init() {
                this.loadCategories();
            },

            toggleFilters() {
                this.showFilters = !this.showFilters;
            },

            async loadCategories() {
                try {
                    const response = await this.makeRequest('{{ route("products.get-categories") }}', {});
                    if (response.success) {
                        this.categories = response.data;
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des catégories:', error);
                }
            },

            // Gestion des images
            handleImageSelect(event) {
                this.addImages(event.target.files);
            },

            handleImageDrop(event) {
                this.addImages(event.dataTransfer.files);
            },

            addImages(files) {
                const maxFiles = 5;
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

                Array.from(files).forEach(file => {
                    if (this.selectedImages.length >= maxFiles) {
                        this.showNotification('error', `Maximum ${maxFiles} images autorisées`);
                        return;
                    }

                    if (file.size > maxSize) {
                        this.showNotification('error', `${file.name} est trop volumineux (max 5MB)`);
                        return;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        this.showNotification('error', `${file.name} n'est pas un format d'image autorisé`);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImages.push({
                            file: file,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                });
            },

            removeSelectedImage(index) {
                this.selectedImages.splice(index, 1);
            },

            removeExistingImage(index) {
                if (!this.imagesToRemove.includes(index)) {
                    this.imagesToRemove.push(index);
                }
                if (this.formData.images) {
                    let images = this.getImagesArray(this.formData.images);
                    images.splice(index, 1);
                    this.formData.images = images;
                }
            },

            setMainImage(index) {
                if (this.formData.images) {
                    let images = this.getImagesArray(this.formData.images);
                    images.forEach((img, i) => {
                        img.is_main = (i === index);
                    });
                    this.formData.images = images;
                }
            },

            getImagesArray(images) {
                if (!images) return [];
                return Array.isArray(images) ? images : JSON.parse(images);
            },

            openCreateModal() {
                this.isEditing = false;
                this.selectedImages = [];
                this.imagesToRemove = [];
                this.formData = {
                    id: '',
                    name: '',
                    description: '',
                    price: '',
                    stock_quantity: 0,
                    category_id: '',
                    is_active: true,
                    images: null
                };
                this.showFormModal = true;
            },

            async openEditModal(id) {
                this.loading = true;
                try {
                    const response = await this.makeRequest('{{ route("products.edit") }}', { id });
                    if (response.success) {
                        this.isEditing = true;
                        this.selectedImages = [];
                        this.imagesToRemove = [];
                        this.formData = {
                            id: response.data.id,
                            name: response.data.name,
                            description: response.data.description || '',
                            price: response.data.price,
                            stock_quantity: response.data.stock_quantity,
                            category_id: response.data.category_id,
                            is_active: response.data.is_active == 1,
                            images: response.data.images
                        };
                        this.showFormModal = true;
                    } else {
                        this.showNotification('error', response.message);
                    }
                } catch (error) {
                    this.showNotification('error', 'Erreur lors du chargement du produit');
                } finally {
                    this.loading = false;
                }
            },

            async openShowModal(id) {
                this.loading = true;
                try {
                    const response = await this.makeRequest('{{ route("products.edit") }}', { id });
                    if (response.success) {
                        this.viewData = response.data;
                        this.showViewModal = true;
                    } else {
                        this.showNotification('error', response.message);
                    }
                } catch (error) {
                    this.showNotification('error', 'Erreur lors du chargement du produit');
                } finally {
                    this.loading = false;
                }
            },

            openDeleteModal(id, name) {
                this.deleteItem = { id, name };
                this.showDeleteModal = true;
            },

            closeFormModal() {
                this.showFormModal = false;
            },

            closeViewModal() {
                this.showViewModal = false;
                this.viewData = null;
            },

            async submitForm() {
                if (!this.formData.name.trim()) {
                    this.showNotification('error', 'Le nom du produit est obligatoire');
                    return;
                }

                if (!this.formData.price || parseFloat(this.formData.price) <= 0) {
                    this.showNotification('error', 'Le prix doit être supérieur à 0');
                    return;
                }

                if (!this.formData.category_id) {
                    this.showNotification('error', 'Veuillez sélectionner une catégorie');
                    return;
                }

                this.loading = true;
                try {
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    const url = this.isEditing ? '{{ route("products.update") }}' : '{{ route("products.store") }}';

                    // Ajouter les données du formulaire
                    for (const key in this.formData) {
                        if (key === 'is_active') {
                            if (this.formData[key]) formData.append(key, '1');
                        } else if (key !== 'images') {
                            formData.append(key, this.formData[key]);
                        }
                    }

                    // Ajouter les nouvelles images
                    this.selectedImages.forEach(imageData => {
                        formData.append('images[]', imageData.file);
                    });

                    // Ajouter les images à supprimer
                    if (this.imagesToRemove.length > 0) {
                        formData.append('remove_images', JSON.stringify(this.imagesToRemove));
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.success) {
                        this.showNotification('success', data.message);
                        this.showFormModal = false;
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showNotification('error', data.message);
                    }
                } catch (error) {
                    this.showNotification('error', 'Erreur lors de l\'opération');
                } finally {
                    this.loading = false;
                }
            },

            async confirmDelete() {
                this.loading = true;
                try {
                    const response = await this.makeRequest('{{ route("products.destroy") }}', { id: this.deleteItem.id });

                    if (response.success) {
                        this.showNotification('success', response.message);
                        this.showDeleteModal = false;
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showNotification('error', response.message);
                    }
                } catch (error) {
                    this.showNotification('error', 'Erreur lors de la suppression');
                } finally {
                    this.loading = false;
                }
            },

            async makeRequest(url, data) {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                for (const key in data) {
                    if (key === 'is_active') {
                        if (data[key]) formData.append(key, '1');
                    } else {
                        formData.append(key, data[key]);
                    }
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                return await response.json();
            },

            showNotification(type, message) {
                this.notification = { show: true, type, message };
                setTimeout(() => {
                    this.notification.show = false;
                }, 5000);
            }
        }
    }
</script>
@endsection
