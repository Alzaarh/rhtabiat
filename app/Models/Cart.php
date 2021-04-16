<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_price
 * @property-read mixed $total_weight
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    use HasFactory;

    public function getTotalPriceAttribute()
    {
        return $this
            ->products
            ->load('product')
            ->reduce(
                fn ($carry, $item) =>
                $carry +
                (
                    $item->price *
                    ((100 - $item->product->off) / 100) *
                    $item->pivot->quantity
                ),
                0
            );
    }

    public function getTotalWeightAttribute()
    {
        return $this
            ->products
            ->load('product')
            ->reduce(
                fn ($carry, $item) =>
                $carry + $item->weight * $item->pivot->quantity,
                0
            );
    }

    public function products()
    {
        return $this->belongsToMany(ProductItem::class, 'cart_product_item')
            ->withPivot('quantity');
    }
}
