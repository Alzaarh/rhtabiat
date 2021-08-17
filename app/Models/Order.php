<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Morilog\Jalali\Jalalian;

class Order extends Model
{
    use HasFactory;

    const STATUS = [
        'not_paid' => 1,
        'being_processed' => 2,
        'in_post_office' => 3,
        'delivered' => 4,
        'rejected' => 5,
    ];

    public const STATUS_FA = [
        1 => 'در انتظار پرداخت',
        2 => 'در حال پردازش',
        3 => 'تحویل به شرکت پست',
        4 => 'تحویل به مشتری',
        5 => 'رد شده',
    ];
    public const WITHIN_PROVINCE = 11;
    public const DELIVERY_THRESHOLD = 450000;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);

        static::creating(function ($order) {
            if (!isset($order->status)) {
                $order->status = static::STATUS['not_paid'];
            }
            $order->code = static::count() . rand(10000, 99999);
            $order->visitor = request()->ip();
        });
    }

    public function getPriceAttribute(): int
    {
        $off = 0;
        $price = $this->items->reduce(fn ($c, $i) => $i->pivot->price * (100 - $i->pivot->off) / 100 * $i->pivot->quantity + $c, 0);
        $priceWithoutOff = $this->items->reduce(fn ($c, $i) => $i->pivot->price * $i->pivot->quantity + $c, 0);
        if (filled($this->discountCode)) {
            $off = $this->discountCode->calc($priceWithoutOff);
        }
        return $price - $off + $this->delivery_cost + $this->package_price;
    }

    public function getProductsPriceAttribute()
    {
        return $this->products->reduce(
            fn ($carry, $product) => $product->pivot->price *
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
        return $this->belongsTo(DiscountCode::class)->withoutGlobalScope('valid');
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

    public function getUserAttribute()
    {
        if ($this->forGuest()) {
            return $this->guestDetail->load('city', 'province');
        }
    }

    /**
     * Convert created at column to Persian date.
     *
     * @param Carbon $createdAt
     * @return string
     */
    public function getCreatedAtAttribute(string $createdAt): string
    {
        return Jalalian::fromCarbon(Carbon::make($createdAt))->format('Y/m/d');
    }

    /**
     * Translate status code to persian text.
     *
     * @return string
     */
    public function translateStatus(): string
    {
        switch ($this->status) {
            case 1:
                return 'در انتظار پرداخت';
            case 2:
                return 'در حال پردازش';
            case 3:
                return 'تحویل به شرکت پست';
            case 4:
                return 'تحویل به مشتری';
            case 5:
                return 'رد شده';
            default:
                return '';
        }
    }

    /**
     * Search through orders by order code.
     *
     * @param Builder $builder
     * @param string $orderCode
     * @return Builder
     */
    public function scopeSearch(Builder $builder, string $orderCode): Builder
    {
        return $builder->where('code', 'like', '%' . $orderCode . '%');
    }

    /**
     * Filter orders by status.
     *
     * @param Builder $builder
     * @param integer $status
     * @return Builder
     */
    public function scopeFilter(Builder $builder, int $status): Builder
    {
        return $builder->whereStatus($status);
    }

    /**
     * order is purchased by user.
     *
     * @return BelongsTo
     */
    public function purchasedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * order is purchased by guest.
     *
     * @return BelongsTo
     */
    public function purchasedByGuest(): HasOne
    {
        return $this->hasOne(GuestOrder::class, 'order_id');
    }

    /**
     * Update order status to rejected.
     *
     * @return void
     */
    public function reject(): void
    {
        $this->status = self::STATUS['rejected'];
        $this->save();
    }
}
