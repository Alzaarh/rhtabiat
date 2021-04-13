<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Traits\HasImage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, HasImage;

    protected $fillable = [
        'name',
        'slug',
        'off',
        'image',
        'short_desc',
        'desc',
        'category_id',
    ];

    protected static function booted()
    {
        static::updating(fn ($product) => self::deleteImage($product));
        static::deleting(fn ($product) => self::deleteImage($product));
    }

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug);
    }

    public function getPriceAttribute()
    {
        return !$this->hasMultipleItems() ? $this->items()->value('price') : null;
    }

    public function getMinPriceAttribute()
    {
        return $this->hasMultipleItems() ? $this->items()->reorder('price', 'asc')->value('price') : null;
    }

    public function getMaxPriceAttribute()
    {
        return $this->hasMultipleItems() ? $this->items()->reorder('price', 'desc')->value('price') : null;
    }

    public function getAvgScoreAttribute()
    {
        return $this->comments()->count() > 0 ? $this->comments()->avg('score') : 0;
    }

    public function scopeWherePriceIsGreater($query, $price)
    {
        return $query->whereHas('items', fn ($query) => $query->where('price', '>=', $price));
    }

    public function scopeWherePriceIsLess($query, $price)
    {
        return $query->whereHas('items', fn ($query) => $query->where('price', '<=', $price));
    }

    public function scopeHasDiscount($query)
    {
        return $query->whereNotNull('off');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get the items of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ProductItem::class)->orderBy('weight', 'asc');
    }

    /**
     * Get the comments of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function hasContainer(): bool
    {
        return $this->items->contains(
            fn ($item) => filled($item->container)
        );
    }

    public function getZinkItems()
    {
        return $this->items->where('container', ProductItem::ZINK_CONTAINER);
    }

    public function getPlasticItems()
    {
        return $this->items->where('container', ProductItem::PLASTIC_CONTAINER);
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
