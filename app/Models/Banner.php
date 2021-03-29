<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted()
    {
        static::updating(function (self $banner) {
            Storage::delete($banner->getRawOriginal('image'));
        });
        static::deleting(function (self $banner) {
            Storage::delete($banner->getRawOriginal('image'));
        });
    } 

    public function getImageAttribute($value)
    {
        return storage() . $value;
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActive()
    {
        return self::isActive()->first();
    }
}
