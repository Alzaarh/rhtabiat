<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $casts = ['code' => 'string'];

    protected $guarded = [];

    public $timestamps = false;

    public const USAGES = [
        'register' => 1,
        'login' => 2,
    ];

    protected static function booted()
    {
        static::saving(function ($vcode) {
            $vcode->updated_at = now();
        });
    }

    public function scopeHasPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeIsRegister($query)
    {
        return $query->where('usage', self::USAGES['register']);
    }

    public function scopeIsLogin($query)
    {
        return $query->where('usage', self::USAGES['login']);
    }

    public function scopeHasCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function newRegister($data)
    {
        return self::updateOrCreate(
            ['phone' => $data['phone']],
            array_merge($data, [
                'usage' => self::USAGES['register'],
                'code' => rand(10000, 99999),
            ])
        );
    }

    public function verifyRegister($data)
    {
        $vcode = self::hasPhone($data['phone'])
            ->isRegister()
            ->hasCode($data['code'])
            ->first();
        if (!$vcode || now()->diffInHours($vcode->updated_at) > 1) {
            return false;
        }
        return true;
    }

    public function newLogin($data)
    {
        return self::updateOrCreate(
            ['phone' => $data['phone']],
            array_merge($data, [
                'usage' => self::USAGES['login'],
                'code' => rand(10000, 99999),
            ])
        );
    }

    public function verifyLogin($data)
    {
        $vcode = self::hasPhone($data['phone'])
            ->isLogin()
            ->hasCode($data['code'])
            ->first();
        if (!$vcode || now()->diffInHours($vcode->updated_at) > 1) {
            return false;
        }
        return true;
    }
}
