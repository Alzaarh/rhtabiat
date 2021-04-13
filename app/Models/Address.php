<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

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
