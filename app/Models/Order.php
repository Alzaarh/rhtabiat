<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $address_id
 * @property int|null $user_id
 * @property int $status
 * @property string $code
 * @property int $delivery_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address $address
 * @property-read mixed $total_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

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
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->status = self::STATUS_LIST['not_paid'];
            $order->code = Str::of('#')
                              ->append(self::count())
                              ->append('-')
                              ->append(Str::random(10));
        });
    }

    public function getTotalPriceAttribute()
    {
        $productsPrice = $this->products->reduce(
            fn($carry, $product) => $product->pivot->price *
                (100 - $product->pivot->off) / 100 *
                $product->pivot->quantity + $carry,
            0
        );
        return $productsPrice + $this->delivery_cost;
    }

    public function products()
    {
        return $this
            ->belongsToMany(ProductItem::class, 'order_product_item')
            ->withPivot(
                [
                    'price',
                    'off',
                    'quantity',
                    'weight',
                ]
            );
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
