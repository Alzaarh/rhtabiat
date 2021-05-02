<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($guest) {
            $guest->token = Str::uuid();
        });
    }

    public function orders()
    {
        return $this->hasMany(GuestOrder::class);
    }
}
