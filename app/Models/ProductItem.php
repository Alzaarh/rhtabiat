<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = ['weight', 'price', 'quantity', 'container'];

    protected $touches = ['carts'];

    public $timestamps = false;

    const ZINK_CONTAINER = 1;
    const PLASTIC_CONTAINER = 2;

    /**
     * Set the correct container value
     *
     * @param string $container
     * @return void
     */
    public function setContainerAttribute(string $container): void
    {
        $container === 'zink'
        ? $this->attributes['container'] = self::ZINK_CONTAINER
        : $this->attributes['container'] = self::PLASTIC_CONTAINER;
    }

    /**
     * Get the product that owns the item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function getContainerFarsi()
    {
        if (empty($this->container)) {
            return null;
        }
        return $this->container === self::ZINK_CONTAINER
        ? 'روحی'
        : 'پلاستیکی';
    }
}
