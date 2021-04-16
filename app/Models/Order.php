<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS_LIST = [
        'not_paid' => 1,
        'being_processed' => 2,
        'in_post_office' => 3,
        'delivered' => 4,
    ];

    public const STATUS_LIST_FA = [
        'در انتظار پرداخت' => 1,
        'در حال پردازش' => 2,
        'تحویل به شرکت پست' => 3,
        'تحویل به مشتری' => 4,
    ];

    /**
     * If province_id is equal to this, order is within province.
     *
     * @var int
     */
    const WHITHIN_PROVINCE = 11;

    public function getTotalPriceAttribute()
    {
        return $this
            ->products
            ->reduce(
                fn ($carry, $product) =>
                    ($product->pivot->price *
                    ((100 - $product->pivot->off) / 100) *
                    $product->pivot->quantity) + $carry,
                0
            );
    }

    public function products()
    {
        return $this
            ->belongsToMany(ProductItem::class, 'order_product_item')
            ->withPivot([
                'price',
                'off',
                'quantity',
                'weight',
            ]);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public static function generateCode()
    {
        return Str::of('#')
            ->append(self::count())
            ->append('-')
            ->append(Str::random(10));
    }
}
