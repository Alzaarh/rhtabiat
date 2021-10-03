<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public const USAGES = [
        'register' => 1,
        'login' => 2,
        "forget" => 3,
        "change_pass" => 4,
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
}
