{{-- resources/views/products/modals.blade.php --}}

<!-- Modal Formulaire (Création/Édition) -->
<div x-show="showFormModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeFormModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white" x-text="isEditing ? 'Modifier le produit' : 'Nouveau produit'"></h3>
                    <button @click="closeFormModal()" class="text-white hover:text-gray-200 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="bg-white px-6 py-6 max-h-96 overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Colonne gauche: Informations de base -->
                    <div class="space-y-4">
                        <!-- Nom -->
                        <div>
                            <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom du produit <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="product_name"
                                   x-model="formData.name"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Entrez le nom du produit">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="product_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="product_description"
                                      x-model="formData.description"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Description du produit (optionnel)"></textarea>
                        </div>

                        <!-- Prix et Stock -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="product_price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prix <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="product_price"
                                       x-model="formData.price"
                                       step="0.01"
                                       min="0.01"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="0.00">
                            </div>
                            <div>
                                <label for="product_stock" class="block text-sm font-medium text-gray-700 mb-1">
                                    Stock <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="product_stock"
                                       x-model="formData.stock_quantity"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="0">
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="product_category" class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select id="product_category"
                                    x-model="formData.category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionner une catégorie</option>
                                <template x-for="category in categories" :key="category.id">
                                    <option :value="category.id" x-text="category.name"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Statut -->
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="product_active"
                                   x-model="formData.is_active"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="product_active" class="ml-2 block text-sm text-gray-700">
                                Produit actif
                            </label>
                        </div>
                    </div>

                    <!-- Colonne droite: Gestion des images -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Images du produit</label>

                            <!-- Zone de drop pour nouvelles images -->
                            <div @drop.prevent="handleImageDrop($event)"
                                 @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
                                 @dragleave.prevent="$event.currentTarget.classList.remove('drag-over')"
                                 class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="mt-2">
                                    <label for="product_images" class="cursor-pointer">
                                        <span class="text-blue-600 hover:text-blue-500 font-medium">Cliquez pour sélectionner</span>
                                        <span class="text-gray-500"> ou glissez-déposez</span>
                                        <input type="file"
                                               id="product_images"
                                               @change="handleImageSelect($event)"
                                               multiple
                                               accept="image/*"
                                               class="hidden">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Max 5 images, 5MB chacune</p>
                            </div>

                            <!-- Images existantes (en mode édition) -->
                            <div x-show="isEditing && formData.images" class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Images existantes</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(image, index) in getImagesArray(formData.images)" :key="index">
                                        <div class="relative group" x-show="!imagesToRemove.includes(index)">
                                            <img :src="'{{ Storage::url('') }}' + image.path"
                                                 :alt="image.original_name"
                                                 class="w-full h-20 object-cover rounded-lg border">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition duration-200 flex items-center justify-center">
                                                <div class="opacity-0 group-hover:opacity-100 flex space-x-1">
                                                    <button @click="setMainImage(index)"
                                                            :class="image.is_main ? 'bg-yellow-500' : 'bg-blue-500'"
                                                            class="p-1 rounded text-white text-xs transition duration-200"
                                                            :title="image.is_main ? 'Image principale' : 'Définir comme principale'">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    </button>
                                                    <button @click="removeExistingImage(index)"
                                                            class="bg-red-500 hover:bg-red-600 p-1 rounded text-white text-xs transition duration-200"
                                                            title="Supprimer">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Nouvelles images sélectionnées -->
                            <div x-show="selectedImages.length > 0" class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Nouvelles images</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(imageData, index) in selectedImages" :key="index">
                                        <div class="relative group">
                                            <img :src="imageData.preview"
                                                 :alt="imageData.file.name"
                                                 class="w-full h-20 object-cover rounded-lg border">
                                            <button @click="removeSelectedImage(index)"
                                                    class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition duration-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                <button @click="closeFormModal()"
                        type="button"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Annuler
                </button>
                <button @click="submitForm()"
                        :disabled="loading"
                        type="button"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-all duration-200">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Traitement...' : (isEditing ? 'Modifier' : 'Créer')"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Visualisation -->
<div x-show="showViewModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeViewModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Détails du produit</h3>
                    <button @click="closeViewModal()" class="text-white hover:text-gray-200 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="bg-white px-6 py-6" x-show="viewData">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informations -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900" x-text="viewData?.name"></h4>
                            <p class="text-gray-600 mt-1" x-text="viewData?.description || 'Aucune description'"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Prix</span>
                                <div class="text-lg font-bold text-gray-900" x-text="parseFloat(viewData?.price || 0).toFixed(2) + ' FCFA'"></div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Stock</span>
                                <div class="text-lg font-bold text-gray-900">
                                    <span x-text="viewData?.stock_quantity"></span> unité<span x-show="viewData?.stock_quantity > 1">s</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Catégorie</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                                      x-text="viewData?.category?.name || 'Sans catégorie'"></span>
                            </div>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Statut</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                      :class="viewData?.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                      x-text="viewData?.is_active ? 'Actif' : 'Inactif'"></span>
                            </div>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Date de création</span>
                            <div class="text-sm text-gray-900" x-text="new Date(viewData?.created_at).toLocaleDateString('fr-FR')"></div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Images</h4>
                        <div x-show="viewData?.images && getImagesArray(viewData.images).length > 0">
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="(image, index) in getImagesArray(viewData?.images || [])" :key="index">
                                    <div class="relative">
                                        <img :src="'{{ Storage::url('') }}' + image.path"
                                             :alt="image.original_name"
                                             onerror="this.src='{{ asset('images/placeholder-product.svg') }}'"
                                             class="w-full h-24 object-cover rounded-lg border">
                                        <div x-show="image.is_main"
                                             class="absolute top-1 left-1 bg-yellow-500 text-white rounded-full p-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div x-show="!viewData?.images || getImagesArray(viewData?.images || []).length === 0"
                             class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2">Aucune image</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button @click="closeViewModal()"
                        type="button"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmation Suppression -->
<div x-show="showDeleteModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showDeleteModal = false"></div>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="bg-white px-6 py-6">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Confirmer la suppression
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer le produit
                                <span class="font-semibold" x-text="deleteItem.name"></span> ?
                                Cette action est irréversible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                <button @click="showDeleteModal = false"
                        type="button"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Annuler
                </button>
                <button @click="confirmDelete()"
                        :disabled="loading"
                        type="button"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 transition-all duration-200">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Suppression...' : 'Supprimer'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notifications -->
<div x-show="notification.show"
     x-transition:enter="transform ease-out duration-300"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
     style="display: none;">
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg x-show="notification.type === 'success'" class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg x-show="notification.type === 'error'" class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900" x-text="notification.message"></p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="notification.show = false" class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
