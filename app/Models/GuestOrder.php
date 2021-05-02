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
        'guest_id',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->guest_id = Guest::whereToken(request()->guest_token)
                ->value('id');
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
