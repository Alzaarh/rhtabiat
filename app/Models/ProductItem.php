<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * value of the container column.
     *
     * @var int
     */
    public const ZINK_CONTAINER = 1;

    /**
     * value of the container column.
     *
     * @var int
     */
    public const PLASTIC_CONTAINER = 2;

    /**
     * Get the product that owns the item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}