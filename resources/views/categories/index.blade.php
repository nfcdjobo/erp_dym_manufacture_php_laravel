{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Catégories - ERP DYM Manufacture')
@section('page-subtitle', 'Gestion des Catégories')

@section('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
</style>
@endsection

@section('content')
<div x-data="categoriesManager()">
    <!-- En-tête -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">Gestion des Catégories</h2>
            <p class="mt-1 text-sm text-gray-500">
                Gérez vos catégories de produits
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button @click="openCreateModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouvelle Catégorie
            </button>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Rechercher une catégorie..."
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

    <!-- Tableau des catégories -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr class="hover:bg-gray-50 animate-fade-in">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    {{ $category->description ?? 'Aucune description' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category->products_count }} produit{{ $category->products_count > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $category->user->full_name ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('categories.show', $category) }}"
                                       class="text-blue-600 hover:text-blue-900 transition duration-200"
                                       title="Voir le détail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button @click="openEditModal('{{ $category->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 transition duration-200"
                                            title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal('{{ $category->id }}', '{{ addslashes($category->name) }}')"
                                            class="text-red-600 hover:text-red-900 transition duration-200"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Pagination -->
            @if($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune catégorie</h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ !empty($search) ? 'Aucun résultat trouvé pour votre recherche.' : 'Commencez par créer votre première catégorie.' }}
                </p>
                @if(empty($search))
                <div class="mt-6">
                    <button @click="openCreateModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouvelle Catégorie
                    </button>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    @include('categories.modals')
</div>
@endsection

@section('scripts')
<script>
    function categoriesManager() {
        return {
            showModal: false,
            showDeleteModal: false,
            isEditing: false,
            loading: false,
            formData: {
                id: '',
                name: '',
                description: '',
                is_active: true
            },
            deleteItem: {
                id: '',
                name: ''
            },
            notification: {
                show: false,
                type: '',
                message: ''
            },

            openCreateModal() {
                this.isEditing = false;
                this.formData = {
                    id: '',
                    name: '',
                    description: '',
                    is_active: true
                };
                this.showModal = true;
            },

            async openEditModal(id) {
                this.loading = true;
                try {
                    const response = await this.makeRequest('{{ route("categories.edit") }}', { id });
                    if (response.success) {
                        this.isEditing = true;
                        this.formData = {
                            id: response.data.id,
                            name: response.data.name,
                            description: response.data.description || '',
                            is_active: response.data.is_active == 1
                        };
                        this.showModal = true;
                    } else {
                        this.showNotification('error', response.message);
                    }
                } catch (error) {
                    this.showNotification('error', 'Erreur lors du chargement de la catégorie');
                } finally {
                    this.loading = false;
                }
            },

            openDeleteModal(id, name) {
                this.deleteItem = { id, name };
                this.showDeleteModal = true;
            },

            closeModal() {
                this.showModal = false;
            },

            async submitForm() {
                if (!this.formData.name.trim()) {
                    this.showNotification('error', 'Le nom de la catégorie est obligatoire');
                    return;
                }

                this.loading = true;
                try {
                    const url = this.isEditing ? '{{ route("categories.update") }}' : '{{ route("categories.store") }}';
                    const response = await this.makeRequest(url, this.formData);

                    if (response.success) {
                        this.showNotification('success', response.message);
                        this.showModal = false;
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showNotification('error', response.message);
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
                    const response = await this.makeRequest('{{ route("categories.destroy") }}', { id: this.deleteItem.id });

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
