<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::updating(function ($category) {
            deleteFromDisk($category->getRawOriginal('icon'));
        });
        static::deleting(function ($category) {
            deleteFromDisk($category->getRawOriginal('icon'));
        });
    }

    public function getIconAttribute($value)
    {
        return filled($value) ? storage() . $value : null;
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function getTopLevel()
    {
        return self::whereNull('parent_id')->get();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }
}
