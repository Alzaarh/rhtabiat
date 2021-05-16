<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pishran\LaravelPersianSlug\HasPersianSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $short_desc
 * @property string $desc
 * @property mixed|null $meta_tags
 * @property string|null $image
 * @property int $off
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $avg_score
 * @property-read mixed $max_price
 * @property-read mixed $min_price
 * @property-read mixed $price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product hasDiscount()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceIsGreater($price)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceIsLess($price)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Product orderByPrice(string $dir)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice(string $op, int $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product orderByScore()
 */
class Product extends Model
{
    use HasFactory;
    use HasPersianSlug;

    protected $fillable = [
        'name',
        'slug',
        'meta_tags',
        'off',
        'image',
        'short_desc',
        'desc',
        'category_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getPriceAttribute()
    {
        return !$this->hasMultipleItems() ? $this->items()->value('price') : null;
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
        return $query->selectRaw('products.*, price * (100-off) / 100 as p')
            ->distinct('products.id')
            ->join('product_items', 'products.id', '=', 'product_id')
            ->orderBy('p', $dir);
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
}
