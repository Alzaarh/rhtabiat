<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class ProductCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::saving(fn ($category) => $category->slug = makeSlug($category->name));
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function imageMobile()
    {
        return $this->belongsTo(Image::class);
    }

    /*

    #--------------------------------------------------------------------------
    # Relationships
    #--------------------------------------------------------------------------

    */

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
