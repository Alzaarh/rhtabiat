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
        return $query->where('off', '>', 0);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function items()
    {
        return $this->hasMany(ProductItem::class)->orderBy('weight', 'asc');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product_item')->withPivot('quantity');
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

    private function hasMultipleItems(): bool
    {
        return $this->items()->count() > 1;
    }
}
