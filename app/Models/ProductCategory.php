<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class ProductCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    /*

    #--------------------------------------------------------------------------
    # Properties
    #--------------------------------------------------------------------------

    */

    protected $fillable = [
        'name',
        'slug',
        'image_id',
        'image_mobile_id',
        'parent_id',
    ];

    /*

    #--------------------------------------------------------------------------
    # Accessors and Mutators
    #--------------------------------------------------------------------------

    */

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getCreatedAt(): string
    {
        return Jalalian::fromCarbon($this->created_at)->ago();
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
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
    # Other Stuff
    #--------------------------------------------------------------------------

    */

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);

        static::saving(function ($productCategory) {
            $slug = makeSlug($productCategory->name);
            $count = 0;

            while ($productCategory->withTrashed()->whereSlug($count > 0 ? $slug . "-$count" : $slug)->exists()) {
                $count++;
            }

            $productCategory->slug = $count > 0 ? $slug . "-$count" : $slug;
        });
    }
}
