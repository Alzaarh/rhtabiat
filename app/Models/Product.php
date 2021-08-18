<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;

class Product extends Model
{
    use HasFactory;

    /*

    #--------------------------------------------------------------------------
    # Constants
    #--------------------------------------------------------------------------

    */

    public const UNITS = [
        'kilogram' => 1,
        'mesghal' => 2,
        'number' => 3,
    ];

    /*

    #--------------------------------------------------------------------------
    # Properties
    #--------------------------------------------------------------------------

    */

    protected $casts = ['is_best_selling' => 'boolean'];

    /*

    #--------------------------------------------------------------------------
    # Relationships
    #--------------------------------------------------------------------------

    */

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function items()
    {
        return $this->hasMany(ProductItem::class)->orderBy('weight', 'asc');
    }

    /*

    #--------------------------------------------------------------------------
    # Accessors and Mutators
    #--------------------------------------------------------------------------

    */

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function getSlug(): string
    {
        return $this->getAttribute('slug');
    }

    public function getShortDescription(): string
    {
        return $this->getAttribute('short_desc');
    }

    public function getPrice(): int
    {
        return $this->getAttribute('price') ?? $this->items()->first()->price / $this->items()->first()->weight;
    }

    public function getOff(): int
    {
        return $this->getAttribute('off');
    }

    public function getIsBestSelling(): bool
    {
        return $this->getAttribute('is_best_selling');
    }

    public function getPackagePrice(): int
    {
        return (int) $this->getAttribute('package_price');
    }

    public function getUnitTranslation(): string
    {
        switch ($this->getAttribute('unit')) {
            case 1:
                return 'کیلوگرم';
            case 2:
                return 'مثقال';
            case 3:
                return 'عدد';
            default:
                return '';
        }
    }

    public function getCreatedAt(): string
    {
        return Jalalian::fromCarbon(Carbon::make($this->getAttribute('created_at')))->ago();
    }

    public function getDescription(): string
    {
        return $this->desc;
    }

    public function getMetaTags()
    {
        return json_decode($this->getAttribute('meta_tags'));
    }

    public function getZincItems(): Collection
    {
        return $this->items->where('container', ProductItem::ZINC_CONTAINER);
    }

    public function getPlasticItems(): Collection
    {
        return $this->items->where('container', ProductItem::PLASTIC_CONTAINER);
    }

    /*

    #--------------------------------------------------------------------------
    # Methdos
    #--------------------------------------------------------------------------

    */

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);
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

    private function hasMultipleItems(): bool
    {
        return $this->items()->count() > 1;
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
        return $query->whereHas('items', fn ($query) => $query->where('price', '>=', $price));
    }

    public function scopeWherePriceIsLess($query, $price)
    {
        return $query->whereHas('items', fn ($query) => $query->where('price', '<=', $price));
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasDiscount(Builder $query): Builder
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
        return $this->items->contains(fn ($item) => filled($item->container));
    }

    public function scopeOrderByPrice(Builder $query, string $ascOrDesc): Builder
    {
        return $query->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->selectRaw('product_items.price / product_items.weight as basePrice')
            ->orderBy('basePrice', $ascOrDesc);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', '%' . $search . '%');
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
                fn ($query) => $query->whereId($id)
            );
    }

    public function getSimilar()
    {
        return $this->whereCategoryId($this->category_id)
            ->get();
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
