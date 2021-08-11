<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Image;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'meta_tags',
        'off',
        'image_id',
        'short_desc',
        'desc',
        'category_id',
        'is_best_selling',
        'price',
        'package_price',
    ];

    protected $casts = [
        'is_best_selling' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest('updated_at');
        });
    }

    /**
     * price per 1 kilogram
     * @return int
     */
    public function getPriceAttribute(): int
    {
        $firstItem = $this->items()->first();
        return round($firstItem->price / $firstItem->weight);
    }

    public function getMetaTagsAttribute($value)
    {
        return json_decode($value);
    }

    private function hasMultipleItems(): bool
    {
        return $this->items()->count() > 1;
    }

    public function items()
    {
        return $this->hasMany(ProductItem::class)->orderBy('weight', 'asc');
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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeWherePriceIsGreater($query, $price)
    {
        return $query->whereHas('items', fn($query) => $query->where('price', '>=', $price));
    }

    public function scopeWherePriceIsLess($query, $price)
    {
        return $query->whereHas('items', fn($query) => $query->where('price', '<=', $price));
    }

    public function scopeHasDiscount($query)
    {
        return $query->where('off', '>', 0);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product_item')->withPivot('quantity');
    }

    public function hasContainer(): bool
    {
        return $this->items->contains(fn($item) => filled($item->container));
    }

    public function getZincItems()
    {
        return $this->items->where('container', ProductItem::ZINC_CONTAINER);
    }

    public function getPlasticItems()
    {
        return $this->items->where('container', ProductItem::PLASTIC_CONTAINER);
    }

    public function scopeOrderByPrice($query, string $dir)
    {
        return $query->orderBy('price', $dir);
    }

    public function scopeOrderByScore($query)
    {
        return $query->selectRaw('products.*, avg(score) as avg_score')
            ->distinct('products.id')
            ->join(
                'comments',
                'products.id',
                '=',
                'commentable_id'
            )
            ->where('commentable_type', self::class)
            ->groupBy('commentable_id')
            ->orderBy('avg_score', 'desc');
    }

    public function scopeWherePrice($query, string $op, int $value)
    {
        return $query->selectRaw('products.*')
            ->distinct('products.id')
            ->join(
                'product_items',
                'products.id',
                '=',
                'product_items.product_id'
            )
            ->where('product_items.price', $op, $value);
    }

    public function scopeWhereCategoryId($query, $id)
    {
        return $query->where('category_id', $id)
            ->orWhereHas(
                'category.parent',
                fn($query) => $query->whereId($id)
            );
    }

    public function getSimilar()
    {
        return $this->whereCategoryId($this->category_id)
            ->get();
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }


    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeBestSelling(Builder $query): Builder
    {
        return $query->whereIsBestSelling(true);
    }
}
