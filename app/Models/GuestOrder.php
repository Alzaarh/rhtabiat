<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrder extends Model
{
    use HasFactory;

    /*

    #--------------------------------------------------------------------------
    # Properties
    #--------------------------------------------------------------------------

    */

    public $timestamps = false;

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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
