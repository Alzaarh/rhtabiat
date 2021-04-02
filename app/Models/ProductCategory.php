<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasImage;

class ProductCategory extends Model
{
    use HasFactory, HasImage;

    protected $fillable = ['name', 'slug', 'image', 'parent_id'];

    protected static function booted()
    {
        static::saving(fn ($category) => self::deleteImage($category));
        static::deleting(fn ($category) => self::deleteImage($category));
    }

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
