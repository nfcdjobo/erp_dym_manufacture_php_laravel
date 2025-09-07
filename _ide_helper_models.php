<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property bool $is_active
 * @property string|null $description
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $product_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Produit> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Category active()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category search($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property array|null $images
 * @property string $price
 * @property int $stock_quantity
 * @property string $category_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read mixed $formatted_price
 * @property-read mixed $main_image
 * @property-read mixed $stock_status
 * @method static \Illuminate\Database\Eloquent\Builder|Produit active()
 * @method static \Illuminate\Database\Eloquent\Builder|Produit byCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit dateRange($from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Produit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Produit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Produit priceRange($min = null, $max = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Produit search($search)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit stockRange($min = null, $max = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Produit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Produit withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProduit {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property int|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

