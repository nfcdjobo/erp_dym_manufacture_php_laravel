<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'categories' => Category::count(),
            'products' => Produit::count(),
            'active_products' => Produit::where('is_active', true)->count(),
            'stock_value' => Produit::sum(DB::raw('price * stock_quantity')),
        ];

        $recentProducts = Produit::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentProducts', 'topCategories'));
    }
}
