<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrder extends Model
{
    use HasFactory;

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
}