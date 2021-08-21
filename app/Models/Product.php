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
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /*
    #--------------------------------------------------------------------------
    # Constants
    #--------------------------------------------------------------------------
    */

    public const KILOGRAM_UNIT = 1;

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

    protected $fillable = [
        'name',
        'slug',
        'short_desc',
        'desc',
        'image_id',
        'meta_tags',
        'off',
        'is_best_selling',
        'package_price',
        'unit',
    ];

    protected $with = ['image'];

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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
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
        return $this->items->first()->price / $this->items->first()->weight;
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

    public function getUnit(): int
    {
        return $this->unit;
    }

    /*

    #--------------------------------------------------------------------------
    # Other Stuff
    #--------------------------------------------------------------------------

    */

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);
    }

    public function scopeOrderByPrice(Builder $query, string $ascOrDesc): Builder
    {
        return $query->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->selectRaw('price / weight as basePrice')
            ->orderBy('basePrice', $ascOrDesc);
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    /*

    #--------------------------------------------------------------------------
    # Methdos
    #--------------------------------------------------------------------------

    */



    public function getAvgScoreAttribute()
    {
        return $this->comments()->count() > 0 ? round($this->comments()->avg('score')) : 0;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasDiscount(Builder $query): Builder
    {
        return $query->where('off', '>', 0);
    }



    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product_item')->withPivot('quantity');
    }

    public function hasContainer(): bool
    {
        return $this->items->contains(fn ($item) => filled($item->container));
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

    public function getSimilar(): Collection
    {
        return $this->whereCategoryId($this->category->id)->get();
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
