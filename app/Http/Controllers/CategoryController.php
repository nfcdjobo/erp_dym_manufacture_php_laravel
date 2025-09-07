<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $page = $request->get('page', 1);
        $limit = 10;

        $query = Category::with(['user'])
            ->search($search)
            ->withCount('products');

        $totalCategories = $query->count();
        $totalPages = ceil($totalCategories / $limit);

        $categories = $query->orderBy('created_at', 'desc')
            ->paginate($limit);

        if ($request->ajax()) {
            return response()->json([
                'categories' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'total_pages' => $categories->lastPage(),
                    'total' => $categories->total(),
                    'per_page' => $categories->perPage(),
                ]
            ]);
        }

        return view('categories.index', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name,NULL,id,deleted_at,NULL',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ], [
                'name.required' => 'Le nom de la catégorie est obligatoire',
                'name.unique' => 'Une catégorie avec ce nom existe déjà',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            try {
                Category::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'is_active' => $request->has('is_active'),
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie créée avec succès'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la catégorie'
                ]);
            }
        }

        return redirect()->back();
    }

    public function show(Category $category)
    {
        $search = request('search');
        $page = request('page', 1);
        $limit = 12;

        $query = $category->products()
            ->search($search);

        $totalProducts = $query->count();
        $totalPages = ceil($totalProducts / $limit);

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($limit);

        return view('categories.show', compact('category', 'products', 'search'));
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $category = Category::find($request->id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catégorie introuvable'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255|unique:categories,name,' . $request->id . ',id,deleted_at,NULL',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ], [
                'name.required' => 'Le nom de la catégorie est obligatoire',
                'name.unique' => 'Une catégorie avec ce nom existe déjà',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            try {
                $category = Category::find($request->id);
                $category->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'is_active' => $request->has('is_active'),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie modifiée avec succès'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de la catégorie'
                ]);
            }
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $category = Category::find($request->id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catégorie introuvable'
                ]);
            }

            $productCount = $category->products()->count();
            if ($productCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossible de supprimer la catégorie car elle contient {$productCount} produit(s)"
                ]);
            }

            try {
                $category->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie supprimée avec succès'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la catégorie'
                ]);
            }
        }

        return redirect()->back();
    }
}
