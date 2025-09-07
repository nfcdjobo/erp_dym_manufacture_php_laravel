<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryFilter = $request->get('category_filter');
        $statusFilter = $request->get('status_filter');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        $stockMin = $request->get('stock_min');
        $stockMax = $request->get('stock_max');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        $query = Produit::with('category')
            ->search($search)
            ->byCategory($categoryFilter)
            ->byStatus($statusFilter)
            ->priceRange($priceMin, $priceMax)
            ->stockRange($stockMin, $stockMax)
            ->dateRange($dateFrom, $dateTo);

        // Tri
        $validSortColumns = ['name', 'price', 'stock_quantity', 'created_at'];
        if (in_array($sort, $validSortColumns)) {
            $query->orderBy($sort, $order);
        } elseif ($sort === 'category_name') {
            $query->join('categories', 'produits.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $order)
                  ->select('produits.*');
        }

        $products = $query->paginate(10);
        $categories = Category::active()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryFilter', 'statusFilter', 'priceMin', 'priceMax', 'stockMin', 'stockMax', 'dateFrom', 'dateTo', 'sort', 'order'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0.01',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'is_active' => 'boolean',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'name.required' => 'Le nom du produit est obligatoire',
                'price.required' => 'Le prix est obligatoire',
                'price.min' => 'Le prix doit être supérieur à 0',
                'stock_quantity.required' => 'Le stock est obligatoire',
                'stock_quantity.min' => 'Le stock ne peut pas être négatif',
                'category_id.required' => 'Veuillez sélectionner une catégorie',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
                'images.*.image' => 'Les fichiers doivent être des images',
                'images.*.max' => 'Les images ne doivent pas dépasser 5MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            try {
                $images = $this->handleImageUpload($request);

                Produit::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock_quantity' => $request->stock_quantity,
                    'category_id' => $request->category_id,
                    'is_active' => $request->has('is_active'),
                    'images' => $images,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Produit créé avec succès'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du produit'
                ]);
            }
        }

        return redirect()->back();
    }

    public function show(Request $request, Produit $product)
    {
        if ($request->ajax()) {
            $product->load('category');
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }

        return view('products.show', compact('product'));
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $product = Produit::with('category')->find($request->id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit introuvable'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:produits,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0.01',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'is_active' => 'boolean',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            try {
                $product = Produit::find($request->id);
                $currentImages = $product->images ?? [];

                // Gérer les nouvelles images
                $newImages = $this->handleImageUpload($request);
                $updatedImages = array_merge($currentImages, $newImages);

                // Gérer la suppression d'images
                if ($request->has('remove_images')) {
                    $imagesToRemove = json_decode($request->remove_images, true);
                    if (is_array($imagesToRemove)) {
                        foreach ($imagesToRemove as $imageIndex) {
                            if (isset($updatedImages[$imageIndex])) {
                                $this->deleteImage($updatedImages[$imageIndex]);
                                unset($updatedImages[$imageIndex]);
                            }
                        }
                        $updatedImages = array_values($updatedImages);
                    }
                }

                $product->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock_quantity' => $request->stock_quantity,
                    'category_id' => $request->category_id,
                    'is_active' => $request->has('is_active'),
                    'images' => $updatedImages,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Produit modifié avec succès'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du produit'
                ]);
            }
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $product = Produit::find($request->id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit introuvable'
                ]);
            }

            try {
                // Supprimer les images physiques
                if ($product->images) {
                    foreach ($product->images as $image) {
                        $this->deleteImage($image);
                    }
                }

                $product->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Produit supprimé avec succès'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du produit'
                ]);
            }
        }

        return redirect()->back();
    }

    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::active()->orderBy('name')->get(['id', 'name']);
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        }

        return redirect()->back();
    }

    private function handleImageUpload(Request $request)
    {
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/uploads/products', $filename);

                // Redimensionner l'image
                $fullPath = storage_path('app/' . $path);
                $this->resizeImage($fullPath, 800, 600);

                $images[] = [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'is_main' => $index === 0, // Le premier devient l'image principale
                    'path' => 'uploads/products/' . $filename,
                ];
            }
        }

        return $images;
    }

    // private function resizeImage($path, $maxWidth = 800, $maxHeight = 600)
    // {
    //     try {

    //         $image = Image::make($path);

    //         if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
    //             $image->resize($maxWidth, $maxHeight, function ($constraint) {
    //                 $constraint->aspectRatio();
    //                 $constraint->upsize();
    //             });
    //             $image->save($path, 90);
    //         }
    //     } catch (\Exception $e) {
    //         // Continuer même si le redimensionnement échoue
    //     }
    // }

    private function resizeImage($path, $maxWidth = 800, $maxHeight = 600)
{
    try {
        // Vérifier que l'extension GD est disponible
        if (!extension_loaded('gd')) {
            return;
        }

        // Obtenir les informations de l'image
        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return;
        }

        [$width, $height, $type] = $imageInfo;

        // Si l'image est déjà dans les bonnes dimensions
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return;
        }

        // Calculer les nouvelles dimensions en conservant le ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = intval($width * $ratio);
        $newHeight = intval($height * $ratio);

        // Créer l'image source selon le type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($path);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($path);
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($path);
                break;
            default:
                return;
        }

        if (!$source) {
            return;
        }

        // Créer la nouvelle image
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Préserver la transparence pour PNG et GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Redimensionner
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Sauvegarder selon le type
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($destination, $path, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($destination, $path, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($destination, $path);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($destination, $path, 90);
                break;
        }

        // Libérer la mémoire
        imagedestroy($source);
        imagedestroy($destination);

    } catch (\Exception $e) {
        // Continuer même si le redimensionnement échoue
    }
}

    private function deleteImage($image)
    {
        if (isset($image['path'])) {
            $path = str_replace('storage/', 'public/uploads/', $image['path']);
            Storage::delete($path);
        }
    }
}
