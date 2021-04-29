<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $amount
 * @property int $order_id
 * @property string $authority
 * @property string|null $ref_id
 * @property int $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS = [
        'not_verified' => 1,
        'verified' => 2,
        'rejected' => 3,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verify(string $refId): void
    {
        $this->ref_id = $refId . time();
        $this->status = static::STATUS['verified'];
        $this->save();
    }

    public function reject(): void
    {
        $this->status = static::STATUS['rejected'];
        $this->save();
    }
}
