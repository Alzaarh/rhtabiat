<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DiscountCode extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected static function booted()
    {
//        static::addGlobalScope(
//            'valid',
//            fn($b) => $b->where('expires_at', '>=', now()->toDateString())
//                ->whereNull('used_at')
//        );
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
        $off = filled($this->value) ? $this->value : $price * ($this->percent) / 100;
        if (filled($this->max) && $this->max < $off) {
            $off = $this->max;
        }
        return $off;
    }

    public function getGroup(): int
    {
        $code = static::latest()->first();
        if (!$code) {
            return 1;
        }
        return $code->group_id + 1;
    }
}
