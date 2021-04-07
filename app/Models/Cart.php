<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function getCreatedAtAttribute($value)
    {
        return Carbon::create($value)->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::create($value)->toDateTimeString();
    }

    public function getTotalPriceAttribute()
    {
        return $this->products->reduce(function ($carry, $product) {
            return $carry + ($product->price * $product->pivot->quantity);
        }, 0);
    }

    public function products()
    {
        return $this->belongsToMany(
            ProductItem::class,
            'cart_product_item'
        )->withPivot('quantity');
    }
}
