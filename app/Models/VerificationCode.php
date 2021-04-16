<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VerificationCode
 *
 * @property int $id
 * @property string $code
 * @property string $phone
 * @property int $usage
 * @property string $created_at
 * @method static Builder|VerificationCode hasCode($code)
 * @method static Builder|VerificationCode hasPhone($phone)
 * @method static Builder|VerificationCode isLogin()
 * @method static Builder|VerificationCode isRegister()
 * @method static Builder|VerificationCode newModelQuery()
 * @method static Builder|VerificationCode newQuery()
 * @method static Builder|VerificationCode query()
 * @method static Builder|VerificationCode whereCode($value)
 * @method static Builder|VerificationCode whereCreatedAt($value)
 * @method static Builder|VerificationCode whereId($value)
 * @method static Builder|VerificationCode wherePhone($value)
 * @method static Builder|VerificationCode whereUsage($value)
 * @mixin \Eloquent
 */
class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public const USAGES = [
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
}
