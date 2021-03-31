<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    private const COUNT = 10;

    protected $fillable = [
        'title',
        'category_id',
        'short_desc',
        'desc',
        'icon',
    ];

    public function getIconAttribute($value)
    {
        return filled($value) ? storage() . $value : null;
    }

    public function getPriceAttribute()
    {
        return !$this->hasMultiplePrice() ? $this->getLowestPrice() : null;
    }

    public function getMinPriceAttribute()
    {
        return $this->hasMultiplePrice() ? $this->getLowestPrice() : null;
    }

    public function getMaxPriceAttribute()
    {
        return $this->hasMultiplePrice() ? $this->getHighestPrice() : null;
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
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function getFeatured($count = self::COUNT)
    {
        return self::hasDiscount()->take($count)->get();
    }

    private function hasMultiplePrice(): bool
    {
        return $this->features->count() > 1;
    }

    private function getLowestPrice(): int
    {
        return $this->features()->orderBy('price', 'asc')->value('price');
    }

    private function getHighestPrice(): int
    {
        return $this->features()->orderBy('price', 'desc')->value('price');
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
