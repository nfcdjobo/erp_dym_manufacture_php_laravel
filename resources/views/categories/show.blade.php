{{-- resources/views/categories/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Catégorie: ' . $category->name . ' - ERP DYM Manufacture')
@section('page-subtitle', 'Détail Catégorie')

@section('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }

    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .image-placeholder {
        background: linear-gradient(45deg, #f3f4f6 25%, transparent 25%),
                    linear-gradient(-45deg, #f3f4f6 25%, transparent 25%),
                    linear-gradient(45deg, transparent 75%, #f3f4f6 75%),
                    linear-gradient(-45deg, transparent 75%, #f3f4f6 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }
</style>
@endsection

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 3.414V19a1 1 0 0 0 2 0V3.414l7.293 7.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('categories.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Catégories</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $category->name }}</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Informations de la catégorie -->
<div class="bg-white shadow rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                @if(!empty($category->description))
                <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        {{ $category->products_count }} produit{{ $category->products_count > 1 ? 's' : '' }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Créé par {{ $category->user->full_name ?? '' }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $category->created_at->format('d/m/Y') }}
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $category->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>

            <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0">
                <div class="flex space-x-3">
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux catégories
                    </a>
                    <a href="{{ route('products.index', ['category_filter' => $category->id]) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un produit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche pour les produits -->
<div class="mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher un produit dans cette catégorie..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Rechercher
        </button>
    </form>
</div>

<!-- Liste des produits -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-6">
            Produits de la catégorie
            @if(!empty(request('search')))
            - Résultats pour "{{ request('search') }}"
            @endif
        </h2>

        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="product-card bg-white border border-gray-200 rounded-lg overflow-hidden animate-fade-in">
                <!-- Image du produit -->
                <div class="aspect-w-1 aspect-h-1 w-full">
                    @if($product->main_image)
                    <img src="{{ asset($product->main_image['path']) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 image-placeholder flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Contenu -->
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                            @if(!empty($product->description))
                            <p class="mt-1 text-xs text-gray-500 line-clamp-2" title="{{ $product->description }}">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                            @endif
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $product->formatted_price }}</p>
                            <p class="text-xs text-gray-500">Stock: {{ $product->stock_quantity }}</p>
                        </div>
                        <div class="flex space-x-1">
                            <a href="#"
                               class="p-1 text-indigo-600 hover:text-indigo-800 transition duration-200"
                               title="Voir le détail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="p-1 text-indigo-600 hover:text-indigo-800 transition duration-200"
                               title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-8">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit</h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ !empty(request('search')) ? 'Aucun produit trouvé pour votre recherche.' : 'Cette catégorie ne contient aucun produit pour le moment.' }}
            </p>
            @if(empty(request('search')))
            <div class="mt-6">
                <a href="{{ route('products.index', ['category_filter' => $category->id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter le premier produit
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
