<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasImage;

class ProductCategory extends Model
{
    use HasFactory, HasImage;

    /**
     * Get the children for the product category
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
