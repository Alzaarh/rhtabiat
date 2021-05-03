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
 * @property-read Address $address
 * @property-read mixed $total_price
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductItem[] $products
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
 * @property-read DiscountCode|null $discountCode
 * @property-read mixed $products_price
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductItem[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[] $transactions
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
    public const DELIVERY_THRESHOLD = 350000;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(
            function ($order) {
                $order->status = static::STATUS['not_paid'];
                $order->code = static::count() . rand(10000, 99999);
                $order->visitor = request()->ip();
            }
        );
    }

    public function getPriceAttribute(): int
    {
        $off = 0;
        $price = $this->items->reduce(fn($c, $i) => $i->pivot->price * (100 - $i->pivot->off) / 100 * $i->pivot->quantity + $c, 0);
        $priceWithoutOff = $this->items->reduce(fn($c, $i) => $i->pivot->price * $i->pivot->quantity + $c, 0);
        if (filled($this->discountCode)) {
            $off = $this->discountCode->calc($priceWithoutOff);
        }
        return $price - $off + $this->delivery_cost;
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
        return $this->belongsTo(DiscountCode::class);
    }

    public function guestDetail()
    {
        return $this->hasOne(GuestOrder::class, 'order_id');
    }

    public function verify(): void
    {
        $this->status = Order::STATUS['being_processed'];
        $this->items->each(function ($item) {
            $item->quantity -= $item->pivot->quantity;
            $item->save();
        });
        $this->save();

        if ($this->discountCode) {
            $this->discountCode->is_suspended = false;
            $this->discountCode->used_at = now();
            $this->discountCode->save();
        }
    }

    public function forGuest(): bool
    {
        if ($this->guestDetail) {
            return true;
        }
        return false;
    }

    public function forUser(): bool
    {
        if ($this->address) {
            return true;
        }
        return false;
    }
}
