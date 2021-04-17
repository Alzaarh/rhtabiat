<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|DiscountCode newModelQuery()
 * @method static Builder|DiscountCode newQuery()
 * @method static Builder|DiscountCode query()
 * @method static Builder|DiscountCode whereCode($value)
 * @method static Builder|DiscountCode whereCreatedAt($value)
 * @method static Builder|DiscountCode whereExpiresAt($value)
 * @method static Builder|DiscountCode whereId($value)
 * @method static Builder|DiscountCode whereMax($value)
 * @method static Builder|DiscountCode whereMin($value)
 * @method static Builder|DiscountCode wherePercent($value)
 * @method static Builder|DiscountCode whereUsedAt($value)
 * @method static Builder|DiscountCode whereUserId($value)
 * @method static Builder|DiscountCode whereValue($value)
 * @mixin \Eloquent
 */
class DiscountCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function generateCode() : string
    {
        return Str::of(self::count())->append(Str::random(8));
    }
}
