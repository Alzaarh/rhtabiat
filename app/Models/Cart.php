<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
