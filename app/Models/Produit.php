<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperProduit
 */
class Produit extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'produits';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'images',
        'price',
        'stock_quantity',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($cat) use ($search) {
                      $cat->where('name', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }

    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status !== null && $status !== '') {
            return $query->where('is_active', (bool)$status);
        }
        return $query;
    }

    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null && $min !== '') {
            $query->where('price', '>=', $min);
        }
        if ($max !== null && $max !== '') {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeStockRange($query, $min = null, $max = null)
    {
        if ($min !== null && $min !== '') {
            $query->where('stock_quantity', '>=', $min);
        }
        if ($max !== null && $max !== '') {
            $query->where('stock_quantity', '<=', $max);
        }
        return $query;
    }

    public function scopeDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }

    // Accesseurs
    public function getMainImageAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return null;
        }

        // Chercher l'image marquée comme principale
        foreach ($this->images as $image) {
            if (isset($image['is_main']) && $image['is_main']) {
                return $image;
            }
        }

        // Si aucune image principale, retourner la première
        return $this->images[0] ?? null;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', ' ') . ' FCFA';
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'out-of-stock';
        } elseif ($this->stock_quantity <= 10) {
            return 'low-stock';
        }
        return 'in-stock';
    }
}
