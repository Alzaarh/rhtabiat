<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductItem
 *
 * @property int $id
 * @property float $weight
 * @property int $price
 * @property int $quantity
 * @property int|null $container
 * @property int $product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read Product $product
 * @method static \Database\Factories\ProductItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereContainer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereWeight($value)
 * @mixin \Eloquent
 */
class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = ['weight', 'price', 'quantity', 'container'];

    protected $touches = ['carts'];

    public $timestamps = false;

    public const ZINC_CONTAINER = 1;

    public const PLASTIC_CONTAINER = 2;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
