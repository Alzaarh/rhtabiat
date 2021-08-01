<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected static function booted()
    {
        static::saving(function (self $image) {
            // there is no field image in images table
            unset($image->image);
        });
    }

    /**
     * convert field names to their appropriate names
     *
     * @return array
     */
    public static function fieldNames()
    {
        return [
            'alt' => 'متن جایگزین',
            'title' => 'عنوان',
            'short_desc' => 'توضیح کوتاه',
            'desc' => 'توضیح',
            'url' => 'لینک پیوست',
            'image' => 'فایل پیوست',
            'group' => 'دسته بندی',
        ];
    }

    public function getCreatedAtFaAttribute()
    {
        return Jalalian::fromCarbon($this->created_at)->format('%B %d, %Y');
    }

    public function getUpdatedAtFaAttribute()
    {
        return Jalalian::fromCarbon($this->updated_at)->format('%B %d, %Y');
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function productCategoriesMobile()
    {
        return $this->hasMany(ProductCategory::class, 'image_mobile_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
        
    /**
     * if image dimensions are greater than 1200 resize it to 1200
     *
     * @return void
     */
    public function resize()
    {
        $image = \Image::make($this->image);
        $newWidth = $image->width() > 1200 ? 1200 : $image->width();
        $newHeight = $image->height() > 1200 ? 1200 : $image->height();
        $this->image = $image->resize($newWidth, $newHeight);
    }
    
    /**
     * save image to filesystem
     *
     * @return void
     */
    public function upload()
    {
        $image = \Image::make($this->image);
        if (!$this->url) {
            $filepath = storage_path('app/public/images/');
            $filename = Str::of(Str::random(20))->append(static::count() + 1);
            $filename .= '.' . explode('/', $image->mime())[1];
            $image->save($filepath . $filename);
            $this->url = 'images/' . $filename;
        } else {
            $image->save(storage_path('app/public/') . $this->url);
        }
    }
    
    /**
     * delete image from filesystem
     *
     * @return void
     */
    public function deleteImage()
    {
        \Storage::delete($this->url);
    }
    
    /**
     * check if image is in use
     *
     * @return bool
     */
    public function isInUse()
    {
        if (
            $this->banners()->exists() || 
            $this->productCategories()->exists() || 
            $this->productCategoriesMobile()->exists() ||
            $this->products()->exists() || 
            $this->articles()->exists()
        ) {
            return true;
        }
        return false;
    }
}
