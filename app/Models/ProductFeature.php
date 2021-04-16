<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductFeature
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature lightest()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature plastic()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature zink()
 * @mixin \Eloquent
 */
class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'container',
        'value',
        'price',
    ];

    public $timestamps = false;

    public function scopeZink($query)
    {
        return $query->where('container', 'zink');
    }

    public function scopePlastic($query)
    {
        return $query->where('container', 'plastic');
    }

    public function scopeLightest($query)
    {
        return $query->orderBy('weight', 'asc');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
}
