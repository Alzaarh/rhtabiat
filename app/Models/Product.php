<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Traits\HasImage;

class Product extends Model
{
    use HasFactory, Searchable, HasImage;

    /**
     * Get the price attribute of single item products
     *
     * @return int|null
     */
    public function getPriceAttribute(): ?int
    {
        return !$this->hasMultipleItems() ? $this->items()->value('price') : null;
    }

    /**
     * Get the minimum price of multiple item products
     *
     * @return int|null
     */
    public function getMinPriceAttribute()
    {
        return $this->hasMultipleItems() ? $this->items()->reorder('price', 'asc')->value('price') : null;
    }

    /**
     * Get the maximum price of multiple item products
     *
     * @return int|null
     */
    public function getMaxPriceAttribute()
    {
        return $this->hasMultipleItems() ? $this->items()->reorder('price', 'desc')->value('price') : null;
    }

    public function scopeHasDiscount($query)
    {
        return $query->whereNotNull('off');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    /**
     * Get the category that owns the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get the items of the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ProductItem::class)->orderBy('weight', 'asc');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function getFeatured($count = self::COUNT)
    {
        return self::hasDiscount()->take($count)->get();
    }

    /**
     * Check if the product has container.
     *
     * @return bool
     */
    public function hasContainer(): bool
    {
        return $this->items->contains(fn ($item) => filled($item->container));
    }

    /**
     * Check if product has more than one items
     *
     * @return bool
     */
    private function hasMultipleItems(): bool
    {
        return $this->items()->count() > 1;
    }

    public function getBestSelling($count = 10)
    {
        return self::selectRaw('products.*, sum(order_product.quantity) as total')
            ->join('product_features', 'products.id', '=', 'product_features.product_id')
            ->join('order_product', 'order_product.product_feature_id', '=', 'product_features.id')
            ->groupBy('products.id')->orderBy('total', 'desc')->take($count)
            ->get();
    }
}
