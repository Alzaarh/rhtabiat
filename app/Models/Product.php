<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Traits\HasImage;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, Searchable, HasImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'short_desc', 'desc', 'image', 'off', 'category_id'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($product) {
            Storage::delete($product->getRawOriginal('image'));
        });
        static::deleting(function ($product) {
            Storage::delete($product->getRawOriginal('image'));
        });
    }

    /**
     * Get the price of single item product.
     *
     * @return int|null
     */
    public function getPriceAttribute(): ?int
    {
        return !$this->hasMultipleItems() ? $this->items()->value('price') : null;
    }

    /**
     * Get the minimum price of multiple item product.
     *
     * @return int|null
     */
    public function getMinPriceAttribute(): ?int
    {
        return $this->hasMultipleItems() ? $this->items()->reorder('price', 'asc')->value('price') : null;
    }

    /**
     * Get the maximum price of multiple item product.
     *
     * @return int|null
     */
    public function getMaxPriceAttribute(): ?int
    {
        return $this->hasMultipleItems() ? $this->items()->reorder('price', 'desc')->value('price') : null;
    }

    /**
     * Get the average score of the product.
     *
     * @return float
     */
    public function getAvgScoreAttribute(): float
    {
        return $this->comments()->count() > 0 ? $this->comments()->avg('score') : 0;
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
