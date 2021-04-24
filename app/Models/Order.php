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
 * @property int|null $discount_code_id
 * @property string $visitor
 * @property-read \App\Models\DiscountCode|null $discountCode
 * @property-read mixed $products_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order paid()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereVisitor($value)
 */
class Order extends Model
{
    use HasFactory;

    public const STATUS = [
        'not_paid' => 1,
        'being_processed' => 2,
        'in_post_office' => 3,
        'delivered' => 4,
    ];
    public const STATUS_FA = [
        1 => 'در انتظار پرداخت',
        2 => 'در حال پردازش',
        3 => 'تحویل به شرکت پست',
        4 => 'تحویل به مشتری',
    ];
    public const WITHIN_PROVINCE = 11;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->status = self::STATUS['not_paid'];
            $order->code = Str::of('#')
                ->append(self::count(), '-', Str::random(10));
            $order->visitor = request()->ip();
            $order->user_id = auth('user')->id();
        });
    }

    public function getTotalPriceAttribute()
    {
        $productsPrice = $this->items->reduce(
            fn($carry, $product) => $product->pivot->price *
                (100 - $product->pivot->off) / 100 *
                $product->pivot->quantity + $carry,
            0
        );
        return $productsPrice + $this->delivery_cost;
    }

    public function getProductsPriceAttribute()
    {
        return $this->products->reduce(
            fn($carry, $product) => $product->pivot->price *
                (100 - $product->pivot->off) / 100 *
                $product->pivot->quantity + $carry,
            0
        );
    }

    public function scopePaid($query)
    {
        return $query->where('status', '!=', static::STATUS['not_paid']);
    }

    public function items()
    {
        return $this->belongsToMany(ProductItem::class)
            ->withPivot('price', 'off', 'quantity', 'weight');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function discountCode()
    {
        return $this->hasOne(DiscountCode::class);
    }

    public function verify(): void
    {
        $this->status = Order::STATUS['being_processed'];
        $this->products->each(function ($item) {
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        });
        $this->save();
    }
}
