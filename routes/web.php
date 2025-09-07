<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques (authentification)
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});

// Routes protégées (nécessitent une authentification)
Route::middleware(['auth'])->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des catégories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::post('/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update', [CategoryController::class, 'update'])->name('update');
        Route::post('/destroy', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Gestion des produits
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::post('/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/update', [ProductController::class, 'update'])->name('update');
        Route::post('/destroy', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/get-categories', [ProductController::class, 'getCategories'])->name('get-categories');
    });
});

// Redirection par défaut pour les utilisateurs authentifiés
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth');
