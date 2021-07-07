<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected static function booted()
    {
        static::saving(function ($category) {
            $category->slug = makeSlug($category->name);
        });
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
