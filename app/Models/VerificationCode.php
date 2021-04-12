<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    const USAGES = [
        'register' => 1,
        'login' => 2,
    ];

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
}
