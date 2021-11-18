<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class PromoCode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'user_only' => 'boolean',
        'one_per_user' => 'boolean',
        'infinite' => 'boolean',
    ];

    protected $appends = ['valid_date_fa'];

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);

        static::saving(function (self $promoCode) {
            // construct valid_date field from valid_days which is sent from
            // client
            $promoCode->valid_date = now()->addDays($promoCode->valid_days);

            // because we use guarded property and there is no valid_days column
            // in table
            unset($promoCode->valid_days);
        });
    }

    /**
     * construct valid_date_fa field which is the farsi version of valid_date
     * field
     *
     * @return string
     */
    public function getValidDateFaAttribute(): string
    {
        return Jalalian::fromCarbon(Carbon::make($this->valid_date))
            ->format('%B %d %Y');
    }

    /**
     * calculate discount
     * @param int $orderPrice
     * @return int
     */
    public function evaluate(int $orderPrice): int
    {
        if ((int) $this->min > $orderPrice) {
            abort(403, 'مبلغ خرید حداقل باید ' . $this->min . ' باشد');
        }

        $discountUsingPercent = (int) $this->off_percent * $orderPrice / 100;
        $discountUsingValue = (int) $this->off_value;

        $discountUsingPercent = (int) $this->max < $discountUsingPercent ? $this->max : $discountUsingPercent;
        $discountUsingValue = (int) $this->max < $discountUsingValue ? $this->max : $discountUsingValue;

        if ($discountUsingPercent > $discountUsingValue) {
            return $discountUsingPercent;
        } else {
            return $discountUsingValue;
        }
    }

    public function IsPriceGraterThanMin(int $orderCost): bool
    {
        return $orderCost >= $this->min;
    }

    /*
    #--------------------------------------------------------------------------
    # Relationships
    #--------------------------------------------------------------------------
    */

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*

    #--------------------------------------------------------------------------
    # Accessors and Mutators
    #--------------------------------------------------------------------------

     */

    public function getOffPercent(): ?int
    {
        return $this->off_percent;
    }

    public function getOffValue(): ?int
    {
        return $this->off_value;
    }

    /*
    #--------------------------------------------------------------------------
    # Methods
    #--------------------------------------------------------------------------
     */

    public function calculateOff(int $price): int
    {
        return $this->getOffPercent() ? $this->getOffPercent() * $price / 100 : $this->getOffValue();
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->valid_date);
    }

    public function isAvailable(): bool
    {
        $usedCount = Order::valid()
            ->wherePromoCodeId($this->id)
            ->count();
        return $this->infinite || $this->count > $usedCount;
    }
}
