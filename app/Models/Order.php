<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Morilog\Jalali\Jalalian;

class Order extends Model
{
    use HasFactory;


    /*
    #--------------------------------------------------------------------------
    # Constants
    #--------------------------------------------------------------------------
    */

    // This is used to check if order is inside Khorasan province or not.
    public const KHORASAN_PROVINCE_ID = 11;

    // If order price is greater or equal than this number, delivery cost is free.
    public const FREE_DELIVERY_COST_PRICE = 450000;

    public const STATUS = [
        'not_paid' => 1,
        'being_processed' => 2,
        'in_post_office' => 3,
        'delivered' => 4,
        'rejected' => 5,
    ];

    public const NOT_VERIFIED = 1;

    public const REJECTED = 5;

    /*

    #--------------------------------------------------------------------------
    # Properties
    #--------------------------------------------------------------------------

    */

    protected $fillable = [
        'code',
        'delivery_cost',
        'package_price',
        'status',
    ];

    /*

    #--------------------------------------------------------------------------
    # Relationships
    #--------------------------------------------------------------------------

    */

    public function guestOrder()
    {
        return $this->hasOne(GuestOrder::class);
    }

    public function items()
    {
        return $this->belongsToMany(ProductItem::class)->withPivot('price', 'off', 'quantity', 'weight');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

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

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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

    /*

    #--------------------------------------------------------------------------
    # Accessor and Mutators
    #--------------------------------------------------------------------------

    */

    public function setDeliveryCost(int $cost): void
    {
        $this->delivery_cost = $cost;
    }

    public function setPackagePrice(int $price): void
    {
        $this->package_price = $price;
    }

    public function setGuestDetail(array $guestDetail): void
    {
        $this->guestOrder()->create($guestDetail);
    }

    public function getGuestDetail(): ?GuestOrder
    {
        return $this->guestOrder;
    }

    public function setItems(array $items): void
    {
        foreach ($items as $itemId => $item) {
            $this->items()->attach($itemId, [
                'price' => $item['price'],
                'product_id' => $item['product_id'],
                'off' => $item['off'],
                'weight' => $item['weight'],
                'quantity' => $item['quantity'],
            ]);
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getPrice(): int
    {
        $promoCodeOff = 0;

        $price = $this->getItems()->reduce(
            fn (int $carry, ProductItem $item) =>
            $item->getOrderPrice() * (100 - $item->getOrderOff()) / 100 * $item->getOrderQuantity() + $carry,
            0
        );

        $priceWithoutOff = $this->items->reduce(
            fn (int $carry, ProductItem $item) => $item->getOrderPrice() * $item->getOrderQuantity() + $carry,
            0
        );

        if ($this->getPromoCode()) {
            $promoCodeOff = $this->getPromoCode()->calculateOff($priceWithoutOff);
        }

        return $price - $promoCodeOff + $this->getDeliveryCost() + $this->getPackagePrice();
    }

    public function getPriceWithoutOff(): int
    {
        $priceWithoutOff = $this->items->reduce(
            fn (int $carry, ProductItem $item) => $item->getOrderPrice() * $item->getOrderQuantity() + $carry,
            0
        );

        return $priceWithoutOff;
    }

    public function setPromoCode(PromoCode $promoCode): void
    {
        $this->promo_code_id = $promoCode->id;
    }

    public function getPromoCode(): ?PromoCode
    {
        return $this->promoCode;
    }

    public function getDeliveryCost(): int
    {
        return $this->delivery_cost;
    }

    public function getPackagePrice(): int
    {
        return $this->package_price;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getDeliveryCode(): ?string
    {
        return $this->delivery_code;
    }

    /*
    #--------------------------------------------------------------------------
    # Events, Scopes, ...
    #--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);

        static::creating(function (self $order) {
            if (!$order->status) {
                $order->status = self::STATUS['not_paid'];
            }
            $order->code = self::count() . rand(10000, 99999);
        });
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            self::NOT_VERIFIED,
            self::REJECTED,
        ]);
    }

    /*

    #--------------------------------------------------------------------------
    # Methods
    #--------------------------------------------------------------------------

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
}
