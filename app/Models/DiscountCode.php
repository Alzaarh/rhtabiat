<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DiscountCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($code) {
            $code->code = self::generateCode();
        });
    }

    public static function generateCode() : string
    {
        return self::count()  . Str::random(8);
    }
}
