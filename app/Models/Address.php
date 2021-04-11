<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

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

    public function resolveRouteBinding($value, $field = null)
    {
        return auth()->user()->addresses()->where('id', $value)->firstOrFail();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
