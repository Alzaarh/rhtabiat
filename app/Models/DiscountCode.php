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
 * @method static Builder|DiscountCode whereValidExpiration()
 * @method static Builder|DiscountCode notUsed()
 */
class DiscountCode extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(
            'valid',
            fn($b) => $b->where('expires_at', '>=', now()->toDateString())
                ->whereNull('used_at')
        );
    }

    public function scopeNotUsed($query)
    {
        return $query->whereNull('used_at');
    }

    public function generateCode(): string
    {
        return Str::of(self::count())->append(Str::random(8));
    }

    public function validate()
    {
        return function ($attribute, $value, $fail) {
            $code = self::whereCode($value)->notUsed()->first();
            if (
                empty($code) ||
                (
                    filled($code->user_id) &&
                    $code->user_id !== auth('user')->id()
                )
            ) {
                $fail('کد تخفیف معتبر نیست');
            }
        };
    }

    public function isValid(): bool
    {
        if (filled($this->user_id) && $this->user_id !== auth('user')->id()) {
            return false;
        }
        return true;
    }

    public function calc(int $price): int
    {
        $off = filled($this->value) ? $this->value : $price * (100 - $this->percent) / 100;
        if (filled($this->max) && $this->max < $off) {
            $off = $this->max;
        }
        return $off;
    }
}
