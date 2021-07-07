<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $company
 * @property string $mobile
 * @property string|null $phone
 * @property string $province_id
 * @property string $city_id
 * @property string $zipcode
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $city
 * @property-read mixed $province
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\AddressFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Query\Builder|Address onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|Address withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Address withoutTrashed()
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company',
        'mobile',
        'phone',
        'province_id',
        'city_id',
        'zipcode',
        'address',
    ];

    public $timestamps = false;

    public function getProvinceAttribute()
    {
        return Province::find($this->province_id)->name;
    }

    public function getCityAttribute()
    {
        return City::find($this->city_id)->name;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return auth()->user()->addresses()->where('id', $value)->firstOrFail();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
