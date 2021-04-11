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

    const STATUS_LIST = [
        'not_paid' => 1,
        'being_processed' => 2,
        'in_post_office' => 3,
        'delivered' => 4,
    ];

    private const STATUS_LIST_FA = [
        'در انتظار پرداخت',
        'در حال پردازش',
        'تحویل به شرکت پست',
        'تحویل به مشتری',
    ];

    const PAYMENT_METHODS = [
        'zarinpal' => 1,
    ];

    /**
     * If province_id is equal to this, order is within province.
     *
     * @var int
     */
    const WHITHIN_PROVINCE = 11;

    public function getTotalPriceAttribute()
    {
        return $this->products->reduce(function ($carry, $item) {
            return ($item->pivot->price * $item->pivot->quantity) + $carry;
        }, 0);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::create($value)->toDateTimeString();
    }

    public function getStatusFaAttribute()
    {
        return self::STATUS_LIST_FA[$this->status - 1];
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
        return Str::of('#')->append(self::latest()->value('id') ?? 0)
            ->append('-')
            ->append(Str::random(10));
    }
}
