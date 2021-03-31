<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    const STATUS_LIST = [
        'waitingForPayment' => 1,
        'beingProcessed' => 2,
        'deliveredToPostOffice' => 3,
        'delivered' => 4,
    ];

    private const STATUS_LIST_FA = [
        'در انتظار پرداخت',
        'در حال پردازش',
        'تحویل به شرکت پست',
        'تحویل به مشتری',
    ];

    const PAYMENT_METHOD = ['zarinpal' => 1];

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

    // public function getTotalWeightAttribute()
    // {
    //     return $this->products->reduce(function ($prev, $curr) {
    //         return filled($prev) ?
    //         $prev->pivot->weight * $prev->pivot->quantity : 0 + $curr->pivot->weight * $curr->pivot->quantity;
    //     });
    // }

    public function products()
    {
        return $this->belongsToMany(ProductFeature::class, 'order_product')->withPivot([
            'price',
            'quantity',
            'weight',
        ]);
    }

    public static function generateCode()
    {
        return Str::of('#')->append(self::latest()->value('id') ?? 0)
            ->append('-')
            ->append(Str::random(10));
    }
}
