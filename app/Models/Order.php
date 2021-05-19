<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
