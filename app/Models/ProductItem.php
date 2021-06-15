<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductItem extends Model
{
    use HasFactory;

    public const ZINC_CONTAINER = 1;
    public const PLASTIC_CONTAINER = 2;
    public $timestamps = false;
    protected $fillable = ['weight', 'price', 'quantity', 'container'];
    protected $touches = ['carts'];

    public function getContainerFaAttribute(): ?string
    {
        if (!$this->container) {
            return null;
        } elseif ($this->container === static::ZINC_CONTAINER) {
            return 'روحی';
        } elseif ($this->container === static::PLASTIC_CONTAINER) {
            return 'پلاستیکی';
        }
        return null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function getOrderItems(array $ids): Collection
    {
        return static::with('product')
            ->find($ids);
    }
}
