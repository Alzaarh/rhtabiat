<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        static::creating(function (self $promoCode) {
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
}
