<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\DiscountCode
 *
 * @property int $id
 * @property string $code
 * @property int|null $max
 * @property int|null $min
 * @property int|null $percent
 * @property int|null $value
 * @property int|null $user_id
 * @property string $created_at
 * @property string $expires_at
 * @property string|null $used_at
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereValue($value)
 * @mixin \Eloquent
 */
class DiscountCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($code) {
            $code->code = self::generateCode();
        });
    }

    public static function generateCode() : string
    {
        return self::count()  . Str::random(8);
    }
}
